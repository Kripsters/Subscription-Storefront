<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
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

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                // Example: get billing & shipping details
                $billingDetails = $session->customer_details ?? null;
                $shippingDetails = $session->shipping ?? null;

                // Save to database (assuming you have a logged-in user before checkout)
                $userId = $session->metadata->user_id ?? null;

                if ($userId) {
                    Subscription::updateOrCreate(
                        ['user_id' => $userId],
                        [
                            'stripe_customer_id' => $session->customer,
                            'stripe_subscription_id' => $session->subscription,
                            'stripe_price_id' => $session->metadata->price_id ?? null,
                            'status' => 'active',
                            'billing_name' => $billingDetails->name ?? null,
                            'billing_email' => $billingDetails->email ?? null,
                            'billing_address' => json_encode($billingDetails->address ?? []),
                            'shipping_address' => json_encode($shippingDetails->address ?? []),
                        ]
                    );
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