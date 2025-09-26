<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Models\SubscriptionOrder;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;




class StripeWebhookController extends Controller
{
    //The one and only function as all events are handled here
    public function handleWebhook(Request $request)
    {
        // The raw JSON payload sent by Stripe
        $payload = $request->getContent();
        // Signature header used to verify payload authenticity
        $sigHeader = $request->header('Stripe-Signature');
        // Secret used to validate signature (configured in Stripe Dashboard)
        $secret = env('STRIPE_WEBHOOK_SECRET');


        try {
            // Verify the webhook signature and construct a strongly-typed Event
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            // If signature verification or parsing fails, log and reject
            Log::error("Stripe Webhook Error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }


        // Log the event metadata.
        // ⚠️ Consider redacting payload or logging only IDs in production to avoid storing PII.
        Log::info('✅ Stripe Event Received', [
            'type' => $event->type,
            'payload' => $event->data->object
        ]);



            

        // Handle the event based on its type
        switch ($event->type) { 

            case 'checkout.session.completed': // Fires when a Checkout Session is successfully completed
                                    // Log the raw Checkout Session object we received in the event
                    Log::info('session: ' . json_encode($event->data->object));

                    $session = $event->data->object;

                    // Read metadata (remember you JSON-encoded `cart` at session creation)
                    $userId = $session->metadata->user_id ?? null;
                    $cartRaw = data_get($session, 'metadata.cart'); // JSON string (may be null)
                    $cart    = $cartRaw ? json_decode($cartRaw, true) : null;
                    $cartId = $session->metadata->cart ?? null;

                    if ($userId && $session->mode === 'subscription' && $session->subscription) {
                        try {
                            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                                // ✅ Retrieve with correct PHP signature + expansions
                                $subscription = \Stripe\Subscription::retrieve(
                                    $session->subscription,               // ID as string
                                    ['expand' => [
                                        'items.data.price.product',       // so you can read product->name
                                        'latest_invoice.lines.data'
                                    ]]
                                );
                                

                                
                                Log::info('Subscription full', ['subscription' => $subscription]);


                                // --- Price/Product (modern replacement for deprecated `plan`) ---
                                $item    = $subscription->items->data[0] ?? null;
                                $price   = $item?->price->unit_amount;                   // has unit_amount, currency, recurring
                                
                                // Plan name: prefer Price nickname; fall back to Product name
                                $planName = 'Subscription';
                                
                                // Amount & currency
                                $amount   = isset($price) ? $price / 100 : null;
                                $currency = $item?->price->currency;
                                
                                // Interval
                                $interval = $price?->recurring?->interval ?? null;
                                
                                // Periods: use item-level current_period_{start,end}
                                $periodStart = isset($item->current_period_start)
                                    ? \Carbon\Carbon::createFromTimestamp($item->current_period_start)
                                    : null;
                                
                                $periodEnd = isset($item->current_period_end)
                                    ? \Carbon\Carbon::createFromTimestamp($item->current_period_end)
                                    : null;
                                
                                    $invoice = \Stripe\Invoice::retrieve([
                                        'id' => is_object($subscription->latest_invoice)
                                                 ? $subscription->latest_invoice->id
                                                 : $subscription->latest_invoice,
                                        'expand' => ['lines.data.price.product'],
                                    ]);
                                    
                                    $subLine = collect($invoice->lines->data)
                                               ->firstWhere('proration', false) ?? $invoice->lines->data[0] ?? null;
                                    
                                    $linePeriodStart = $subLine?->period?->start;
                                    $linePeriodEnd   = $subLine?->period?->end;
                                    
                                if (is_object($subscription->latest_invoice)) {
                                    $lines = $subscription->latest_invoice->lines->data ?? [];
                                    // find the subscription line (non-proration)
                                    foreach ($lines as $line) {
                                        if (($line->type ?? null) === 'subscription' && empty($line->proration)) {
                                            $linePeriodStart = $line->period->start ?? null;
                                            $linePeriodEnd   = $line->period->end   ?? null;
                                            break;
                                        }
                                    }
                                }

                                // --- Build your local payload with guaranteed values ---
                                $subscription_data = [
                                    'user_id'                => $userId,
                                    'stripe_customer_id'     => $session->customer,
                                    'stripe_subscription_id' => $subscription->id,
                                    'stripe_price_id'        => $price?->id ?? data_get($session, 'metadata.price_id'),
                                    'status'                 => $subscription->status,
                                    'plan_name'              => $planName,
                                    'amount'                 => $amount,
                                    'currency'               => $currency,
                                    'interval'               => $interval,
                                    'current_period_start'   => $periodStart,
                                    'current_period_end'     => $periodEnd,
                                    'billing_name'           => data_get($session, 'customer_details.name'),
                                    'billing_email'          => data_get($session, 'customer_details.email'),
                                ];



                            // Prefer latest_invoice from the expanded subscription
                            $invoiceId = is_object($subscription->latest_invoice)
                            ? $subscription->latest_invoice->id
                            : $subscription->latest_invoice;

                        $paymentIntentId = null;
                        if (is_object($subscription->latest_invoice) && isset($subscription->latest_invoice->payment_intent)) {
                            $paymentIntentId = is_object($subscription->latest_invoice->payment_intent)
                                ? $subscription->latest_invoice->payment_intent->id
                                : $subscription->latest_invoice->payment_intent;
                        }

                            Log::info('Subscription Data: ' . json_encode($subscription_data));

                            // Upsert your local subscription
                            $subscriptionModel = Subscription::updateOrCreate(
                                ['user_id' => $userId],
                                $subscription_data
                            );

                            $cartItems = CartItem::where('cart_id', $cartId)->get();
                            Log::info('Cart Items for Order: ' . $cartItems->toJson());

                            if ($subscriptionModel) { // Ensure the subscription exists
                                foreach ($cartItems as $item) {
    
                                    // TODO: Eager-load products and ensure $subscription is set before this block.
                                    // Store each item as a SubscriptionOrder
                                    SubscriptionOrder::updateOrCreate([
                                        'subscription_id' => $subscription->id,
                                        'product_id' => $item->product_id,
                                    ], [
                                        'subscription_id' => Subscription::where('user_id', $userId)->first()->id,
                                        'product_id' => $item->product_id,
                                        'product_name' => Product::find($item->product_id)->title,
                                        'quantity' => $item->quantity,
                                    ]);
                                }
                            } else {
                                // If subscription creation failed, log and skip order creation
                                Log::warning("Skipping SubscriptionOrder creation: Subscription not found for user_id {$userId}");
                            }

                            // Save addresses (as JSON strings)
                            Address::updateOrCreate(
                                ['user_id' => $userId],
                                [
                                    'billing'  => json_encode(data_get($session, 'customer_details.address', [])),
                                    'shipping' => json_encode(data_get($session, 'shipping.address', [])),
                                ]
                            );

                            // Record the initial payment (if invoice & amount are known)
                            PaymentHistory::create([
                                'user_id'                 => $userId,
                                'stripe_payment_intent_id'=> $paymentIntentId,
                                'stripe_invoice_id'       => $invoiceId,
                                'amount'                  => $amount,
                                'currency'                => $currency,
                                'status'                  => 'paid', // or check invoice/payment intent status if you want to be exact
                                'paid_at'                 => \Carbon\Carbon::now(),
                                'raw_data'                => json_encode($session), // keep the raw session for audit
                            ]);

                            // Notify the user
                            $user = User::find($userId);
                            $user?->notify(new PaymentSuccessNotification(
                                $amount,
                                data_get($session, 'customer_details.name'),
                                json_encode(data_get($session, 'customer_details.address', [])),
                                json_encode(data_get($session, 'shipping.address', []))
                            ));

                            // Clear the cart
                            $cart = Cart::firstOrCreate(
                                ['user_id' => $userId, 'status' => 'active']
                            )->load('items.product');
    
                            $cart->items()->delete();
                            $cart->delete();

                        } catch (\Throwable $e) {
                            Log::error('Webhook processing error: ' . $e->getMessage());
                            // Optional: capture to an error reporting service
                        }
                    }
                break; // end of success case


                // Payment failed webhook
            case 'invoice.payment_failed':
                // Fires when an invoice payment attempt fails (e.g., card declined)

                // Retrieve the invoice and associated subscription
                $invoice = $event->data->object;
                $subscriptionId = $invoice->subscription;
                // Retrieve the Checkout Session to get metadata (like user_id)
                $userId = $session->metadata->user_id ?? null;

                // Mark local subscription as past_due
                Subscription::where('stripe_subscription_id', $subscriptionId)
                    ->update(['status' => 'past_due']);

                // Record failed payment
                PaymentHistory::create([
                    'user_id' => $userId,
                    'stripe_invoice_id' => $invoice->id,
                    'amount' => $invoice->amount_due / 100,
                    'currency' => strtoupper($invoice->currency),
                    'status' => 'failed',
                    'paid_at' => null,
                    'raw_data' => json_encode($invoice),
                ]);

                break;


                // Subscription canceled webhook
            case 'customer.subscription.deleted':

                // Fires when a subscription is canceled (by you or via dunning)
                $subscription = $event->data->object;

                // Update subscription to canceled
                Subscription::where('stripe_subscription_id', $subscription->id)
                    ->update(['status' => 'canceled']);
                break;

            // TODO: Consider handling:
            // - invoice.paid / invoice.payment_succeeded (to record recurring payments)
            // - customer.subscription.updated (plan/quantity changes)
            // - payment_intent.succeeded (for one-time payments via Checkout)
        }

        // Return a 200 response to acknowledge receipt of the event
        return response()->json(['status' => 'success'], 200);
    }
}
