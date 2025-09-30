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
use Stripe\Invoice;
use Stripe\Subscription as StripeSubscription;
use Carbon\Carbon;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;




class StripeWebhookController extends Controller
{
        /**
     * Extracts the subscription ID from a Stripe Invoice object (object or array).
     */
    function extractSubscriptionIdFromInvoice($invoice) {
        // 1) Standard subscription field
        $sub = $invoice->subscription ?? null;
        if (is_string($sub)) return $sub;
        if (is_object($sub) && isset($sub->id)) return $sub->id;

        // 2) Parent > subscription_details > subscription
        $fromParent = data_get($invoice, 'parent.subscription_details.subscription');
        if (is_string($fromParent)) return $fromParent;

        // 3) Lines > parent > subscription_item_details > subscription
        foreach (($invoice->lines->data ?? []) as $line) {
            $sid = data_get($line, 'parent.subscription_item_details.subscription');
            if (is_string($sid)) return $sid;
        }

        return null;
    }

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

        
        $type = $event->type;
        $obj  = $event->data->object; // thin object (Invoice, Subscription, etc.)
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));



        // Log the event metadata.
        // ⚠️ Consider redacting payload or logging only IDs in production to avoid storing PII.
        Log::info('✅ Stripe Event Received', [
            'type' => $event->type,
            'payload' => $event->data->object
        ]);

        
        Log::info('Event', ['type' => $event->type]);
        Log::info('Raw subscription data', [
            'data' => $event->data ?? null,
        ]);
        Log::info('Raw subscription from event', [
            'value' => $event->data->object->subscription ?? null,
            'type'  => gettype($event->data->object->subscription ?? null),
        ]);

        

        $handleable = [
            'checkout.session.completed',
            'invoice.paid',
            'invoice.payment_failed',
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
            'customer.subscription.trial_will_end',
          ];
          
          if (!in_array($event->type, $handleable, true)) {
              // Log and bail—this avoids trying to pull a subscription ID from events that don't have one
              Log::info('Ignoring event type', ['type' => $event->type]);
              return response('ok', 200);
          }
          
  

        
        // Normalize to a string ID before calling retrieve:
        $subId = null;
        $source = $event->data->object;

        
        if ($event->type === 'checkout.session.completed') {
            $maybe = $source->subscription ?? null;
            $subId = is_object($maybe) ? ($maybe->id ?? null) : $maybe;
        } elseif (in_array($event->type, ['invoice.paid','invoice.payment_failed'])) {
            $maybe = $source->subscription ?? null; // on Invoice
            $subId = is_object($maybe) ? ($maybe->id ?? null) : $maybe;
        } elseif (strpos($event->type, 'customer.subscription.') === 0) {
            $subId = $source->id ?? null; // the event IS the subscription
        }

        Log::info('Normalized subId', ['subId' => $subId]);

        if (in_array($event->type, ['invoice.paid','invoice.payment_failed'])) {

        } else {
            if (!is_string($subId) || strpos($subId, 'sub_') !== 0) {
                Log::warning('Missing/invalid subscription ID, skipping Stripe retrieve', [
                    'event_id' => $event->id,
                    'subId'    => $subId,
                ]);
                return response('ok', 200);
            }
        }
        







            

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
                                    $subId,               // ID as string
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

                            $user->stripe_customer_id = $invoice->customer;
                            $user->save();

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




            
                
        case 'invoice.paid':
        case 'invoice.payment_succeeded': {
                // 1) Re-fetch the invoice with useful expansions
                $invoice = \Stripe\Invoice::retrieve([
                    'id'     => $obj->id,
                    'expand' => [
                        'subscription',          // top-level (might be absent on your API shape)
                        'lines.data',            // we’ll read parent.subscription_item_details + period
                        'payment_intent',
                    ],
                ]);
            
                // 2) Find the Subscription ID wherever it lives in this Invoice shape
                $subId = null;
            
                // A) Standard place (string or object)
                if (isset($invoice->subscription)) {
                    $subId = is_object($invoice->subscription) ? $invoice->subscription->id : $invoice->subscription;
                }
            
                // B) Newer ‘parent.subscription_details.subscription’ (present in your logs)
                if (!$subId) {
                    $subId = data_get($invoice, 'parent.subscription_details.subscription');
                }
            
                // C) Line-level parent (present in your logs)
                if (!$subId && isset($invoice->lines->data)) {
                    foreach ($invoice->lines->data as $line) {
                        $maybe = data_get($line, 'parent.subscription_item_details.subscription');
                        if ($maybe) { $subId = $maybe; break; }
                    }
                }
            
                // 3) Resolve the local user. Your current line triggers the Builder error:
                //    Subscription::where(...)->user_id->first()  // ❌ ‘user_id’ on Builder
                //    Replace with value('user_id') (returns scalar):
                $stripeCustomerId = $invoice->customer;
                $userId = \App\Models\Subscription::where('stripe_customer_id', $stripeCustomerId)
                            ->value('user_id');
            
                // (Optional) If you also store 'stripe_customer_id' on users, prefer users table:
                if (!$userId) {
                    $userId = \App\Models\User::where('stripe_customer_id', $stripeCustomerId)->value('id');
                }
            
                if (!$userId) {
                    Log::warning('invoice.paid: Could not resolve local user for customer', [
                        'customer' => $stripeCustomerId, 'invoice' => $invoice->id
                    ]);
                    return response('ok', 200);
                }
            
                // 4) Get plan/amount/interval from Subscription if we have one,
                //    otherwise from the invoice line’s price (fallback).
                $planName = 'Subscription';
                $amount   = null;
                $currency = null;
                $interval = null;
            
                $periodStartTs = null;
                $periodEndTs   = null;
            
                $priceIdFromLine = data_get($invoice, 'lines.data.0.pricing.price_details.price'); // present in your logs
                $lineForPeriod   = $invoice->lines->data[0] ?? null; // used for period fallback
            
                if ($subId) {
                    // Pull the authoritative subscription snapshot
                    $sub = \Stripe\Subscription::retrieve(
                        $subId,
                        ['expand' => ['items.data.price.product']]
                    );
            
                    $item    = $sub->items->data[0] ?? null;
                    $price   = $item?->price;
                    $product = is_object($price?->product) ? $price->product : null;
            
                    $planName = $price?->nickname ?? ($product->name ?? $planName);
                    $amount   = isset($price->unit_amount) ? $price->unit_amount / 100 : $amount;
                    $currency = isset($price->currency) ? strtoupper($price->currency) : $currency;
                    $interval = $price?->recurring?->interval ?? $interval;
            
                    // Prefer item-level periods on modern API versions
                    $periodStartTs = $item->current_period_start ?? null;
                    $periodEndTs   = $item->current_period_end   ?? null;
                }
            
                // Fallbacks when no subscription (or missing fields) — derive from invoice line
                if (!$amount || !$currency || !$interval || !$planName || !$periodStartTs || !$periodEndTs) {
                    // Periods from invoice line
                    $periodStartTs = $periodStartTs ?: data_get($lineForPeriod, 'period.start');
                    $periodEndTs   = $periodEndTs   ?: data_get($lineForPeriod, 'period.end');
            
                    // Price details from invoice line (then retrieve the Price to read nickname/product if needed)
                    if ($priceIdFromLine && (!$amount || !$currency || !$interval || $planName === 'Subscription')) {
                        $priceObj = \Stripe\Price::retrieve([
                            'id'     => $priceIdFromLine,
                            'expand' => ['product'],
                        ]);
                        $amount   = $amount   ?: ($priceObj->unit_amount ?? null) / 100;
                        $currency = $currency ?: strtoupper($priceObj->currency ?? '');
                        $interval = $interval ?: data_get($priceObj, 'recurring.interval');
                        $planName = ($priceObj->nickname ?? data_get($priceObj, 'product.name')) ?: $planName;
                    }
                }
            
                $periodStart = $periodStartTs ? \Carbon\Carbon::createFromTimestamp($periodStartTs) : null;
                $periodEnd   = $periodEndTs   ? \Carbon\Carbon::createFromTimestamp($periodEndTs)   : null;
            
                // 5) Upsert your local subscription (by user_id)
                \App\Models\Subscription::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'stripe_customer_id'     => $stripeCustomerId,
                        'stripe_subscription_id' => $subId,               // may be null; OK to store or leave previous
                        'stripe_price_id'        => $priceIdFromLine ?? ($priceObj->id ?? null) ?? null,
                        'status'                 => 'active',              // because invoice is paid
                        'plan_name'              => $planName,
                        'amount'                 => $amount,
                        'currency'               => $currency,
                        'interval'               => $interval,
                        'current_period_start'   => $periodStart,
                        'current_period_end'     => $periodEnd,
                    ]
                );
            
                // 6) Record the payment (always)
                try {
                    \App\Models\PaymentHistory::create([
                        'user_id'                 => $userId,
                        'stripe_payment_intent_id'=> is_object($invoice->payment_intent) ? $invoice->payment_intent->id : $invoice->payment_intent,
                        'stripe_invoice_id'       => $invoice->id,
                        'amount'                  => ($invoice->amount_paid ?? 0) / 100,
                        'currency'                => strtoupper($invoice->currency ?? $currency ?? 'EUR'),
                        'status'                  => 'paid',
                        'paid_at'                 => \Carbon\Carbon::createFromTimestamp($invoice->status_transitions->paid_at ?? time()),
                        'raw_data'                => json_encode($invoice),
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Error creating payment history: '.$e->getMessage());
                }
    
        break;
    }
    



                

                case 'customer.subscription.updated': {
                    // $obj is the thin Subscription object from the event
                    // Re-fetch with the fields you need
                    $stripeSub = \Stripe\Subscription::retrieve(
                        $obj->id,
                        ['expand' => ['items.data.price.product']]
                    );
                
                    // Resolve the local user id from the Stripe customer id
                    // Use ->value('id') to avoid the Builder-attrib issue entirely
                    $userId = \App\Models\User::where('stripe_customer_id', $stripeSub->customer)->value('id');
                
                    if (!$userId) {
                        Log::warning('No local user for stripe customer on sub.updated', [
                            'stripe_customer_id' => $stripeSub->customer,
                            'subscription_id'    => $stripeSub->id,
                        ]);
                        return response('ok', 200);
                    }
                
                    // Map price/product
                    $item    = $stripeSub->items->data[0] ?? null;
                    $price   = $item?->price;
                    $product = is_object($price?->product) ? $price->product : null;
                
                    $planName = $price?->nickname ?? ($product->name ?? 'Subscription');
                    $amount   = isset($price->unit_amount) ? $price->unit_amount / 100 : null;
                    $currency = isset($price->currency) ? strtoupper($price->currency) : null;
                    $interval = $price?->recurring?->interval ?? null;
                
                    // Periods: item-level (preferred)
                    $periodStart = isset($item->current_period_start)
                        ? \Carbon\Carbon::createFromTimestamp($item->current_period_start)
                        : null;
                
                    $periodEnd = isset($item->current_period_end)
                        ? \Carbon\Carbon::createFromTimestamp($item->current_period_end)
                        : null;
                
                    // Upsert your local Subscription (use the Model statically)
                    \App\Models\Subscription::updateOrCreate(
                        ['user_id' => $userId],   // <- this is a scalar, not a Builder
                        [
                            'stripe_customer_id'     => $stripeSub->customer,
                            'stripe_subscription_id' => $stripeSub->id,
                            'stripe_price_id'        => $price?->id,
                            'status'                 => $stripeSub->status,
                            'plan_name'              => $planName,
                            'amount'                 => $amount,
                            'currency'               => $currency,
                            'interval'               => $interval,
                            'current_period_start'   => $periodStart,
                            'current_period_end'     => $periodEnd,
                        ]
                    );
                
                    return response('ok', 200);
                }
                

            // TODO: Consider handling:
            // - invoice.paid / invoice.payment_succeeded (to record recurring payments)
            // - customer.subscription.updated (plan/quantity changes)
            // - payment_intent.succeeded (for one-time payments via Checkout)
        }

        // Return a 200 response to acknowledge receipt of the event
        return response()->json(['status' => 'success'], 200);
    }
}
