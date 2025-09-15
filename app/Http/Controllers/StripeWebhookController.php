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

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook Error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Log everything Stripe sends
        Log::info('✅ Stripe Event Received', [
            'type' => $event->type,
            'payload' => $event->data->object
        ]);

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                // Save to database (assuming you have a logged-in user before checkout)
                $userId = $session->metadata->user_id ?? null;
                $cartId = $session->metadata->cart ?? null;

                if ($userId) {
                    try {
                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                        $subscription_info = \Stripe\Subscription::retrieve($session->subscription);
                        $subscription = Subscription::updateOrCreate(
                            ['user_id' => $userId],
                            [
                                'stripe_customer_id' => $session->customer,
                                'stripe_subscription_id' => $session->subscription,
                                'stripe_price_id' => $session->metadata->price_id ?? null,
                                'status' => 'active',
                                'plan_name' => $subscription_info->items->data[0]->plan->nickname,
                                'amount' => $subscription_info->items->data[0]->plan->amount / 100,
                                'currency' => strtoupper($subscription_info->items->data[0]->plan->currency),
                                'interval' => $subscription_info->items->data[0]->plan->interval,
                                'current_period_start' => \Carbon\Carbon::createFromTimestamp($subscription_info->current_period_start),
                                'current_period_end' => \Carbon\Carbon::createFromTimestamp($subscription_info->current_period_end),
                                'billing_name'      => data_get($session, 'customer_details.name'),
                                'billing_email'     => data_get($session, 'customer_details.email'),
                                'billing_address'   => json_encode(data_get($session, 'customer_details.address', [])),
                                'shipping_address'  => json_encode(data_get($session, 'shipping.address', [])),
                            ]
                        );
                    } catch (\Exception $e) {
                        Log::error("DB Save Failed: " . $e->getMessage());
                    }
                    try {
                        $cartItems = CartItem::where('cart_id', $cartId)->get();
                        Log::info('Cart Items for Order: ' . $cartItems->toJson());
                        foreach ($cartItems as $item) {
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
                        $cart = Cart::firstOrCreate(
                            ['user_id' => $userId, 'status' => 'active']
                        )->load('items.product');
                        $cart->items()->delete();

                    } catch (\Exception $e) {
                        Log::error("Order Save Failed: " . $e->getMessage());

                    }
                }
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $subscriptionId = $invoice->subscription;

                Subscription::where('stripe_subscription_id', $subscriptionId)
                    ->update(['status' => 'past_due']);
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;

                Subscription::where('stripe_subscription_id', $subscription->id)
                    ->update(['status' => 'canceled']);
                break;
        }

        return response()->json(['status' => 'success']);
    }

}