<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Models\SubscriptionOrder;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\PaymentHistory;
use App\Models\User;
use App\Notifications\PaymentSuccessNotification;




class StripeWebhookController extends Controller
{
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
                // Log the Checkout Session object
                Log::info('session: ' . json_encode($event->data->object));
                $session = $event->data->object;

                // These are custom values attached to the Checkout Session at creation time
                $userId = $session->metadata->user_id ?? null;
                $cartId = $session->metadata->cart ?? null;

                    
                if ($userId) { // Ensure we have a user ID to associate the subscription and payment history
                    try { // try to save subscription and payment history

                        // Set API key to allow server-side SDK calls
                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                        // Retrieve the Stripe Subscription created by Checkout (if it was a subscription flow)
                        $subscription_info = \Stripe\Subscription::retrieve($session->subscription);


                        // Build local subscription record payload from Stripe data
                        $subscription_data = [
                            'user_id' => $userId,
                            'stripe_customer_id' => $session->customer,
                            'stripe_subscription_id' => $session->subscription,
                            'stripe_price_id' => $session->metadata->price_id ?? null,
                            'status' => 'active',
                            'plan_name' => $subscription_info->items->data[0]->plan->nickname,
                            'amount' => $subscription_info->items->data[0]->plan->amount / 100,
                            'currency' => strtoupper($subscription_info->items->data[0]->plan->currency),
                            'interval' => $subscription_info->items->data[0]->plan->interval,
                            'current_period_start' => $subscription_info->current_period_start ? \Carbon\Carbon::createFromTimestamp($subscription_info->current_period_start) : null,
                            'current_period_end' => $subscription_info->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription_info->current_period_end) : null,

                            // Billing & shipping details captured by Checkout
                            'billing_name'      => data_get($session, 'customer_details.name'),
                            'billing_email'     => data_get($session, 'customer_details.email'),
                            'billing_address'   => json_encode(data_get($session, 'customer_details.address', [])),
                            'shipping_address'  => json_encode(data_get($session, 'shipping.address', [])),
                        ];


                        // Log the subscription data being saved
                        Log::info('Subscription Data: ' . json_encode($subscription_data));


                        // Create or update the local subscription for the user.
                        $subscription = Subscription::updateOrCreate(
                            ['user_id' => $userId],
                            $subscription_data
                        );


                        // Record the initial payment in your ledger/history.
                        // ⚠️ Make this idempotent by unique constraint on payment_intent or invoice ID.
                        PaymentHistory::create([
                            'user_id' => $userId,
                            'stripe_payment_intent_id' => $session->payment_intent,
                            'stripe_invoice_id' => $session->invoice ?? null,
                            'amount' => $subscription_info->items->data[0]->plan->amount / 100,
                            'currency' => strtoupper($subscription_info->items->data[0]->plan->currency),
                            'status' => 'paid',
                            'paid_at' => \Carbon\Carbon::now(),
                            'raw_data' => json_encode($session),
                        ]);


                        // Notify the user about successful payment
                        $user = User::find($userId);
                        $user->notify(new PaymentSuccessNotification(
                            $subscription_data['amount'],
                            data_get($session, 'customer_details.name'),
                            json_encode(data_get($session, 'customer_details.address', [])),
                            json_encode(data_get($session, 'shipping.address', []))
                        ));

                    } catch (\Exception $e) {

                        // Log any failures in saving subscription or payment history
                        Log::error("DB Save Failed: " . $e->getMessage());
                        Log::error("Error Trace: " . $e->getTraceAsString());

                    } // end catch


                    try { // try to create subscription order

                        // Build SubscriptionOrder entries from the user's cart items
                        $cartItems = CartItem::where('cart_id', $cartId)->get();
                        Log::info('Cart Items for Order: ' . $cartItems->toJson());


                        if ($subscription) { // Ensure the subscription exists
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


                        // Clear the user's active cart after converting to subscription order
                        // ⚠️ Ensure the cart belongs to the same user and the conversion succeeded before delete.
                        $cart = Cart::firstOrCreate(
                            ['user_id' => $userId, 'status' => 'active']
                        )->load('items.product');

                        $cart->items()->delete();
                        $cart->delete();

                    } catch (\Exception $e) {
                        Log::error("Order Save Failed: " . $e->getMessage());
                    }
                } // end if $userId
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
