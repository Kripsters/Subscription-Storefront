<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Carbon\Carbon;
class PaymentController extends Controller
{
    // Subscription view (This should be index page)
    public function subscribe()
    {
        return view('subscribe');
    }

    // Process subscription
    public function session(Request $request)
    {
      
    $cart = $request->input('cart');
    $plan = $request->input('plan');

    Stripe::setApiKey(config('services.stripe.secret'));

    switch ($plan) {
        case 'basic':
            $priceId = env('STRIPE_SUBSCRIPTION_PRICE_BASIC');
            break;
        case 'medium':
            $priceId = env('STRIPE_SUBSCRIPTION_PRICE_MEDIUM');
            break;
        case 'advanced':
            $priceId = env('STRIPE_SUBSCRIPTION_PRICE_ADVANCED');
            break;
        default:
            return response()->json(['error' => 'Invalid plan selected'], 400);
    }

    try {
        // Build absolute URLs (route() returns absolute by default if APP_URL is set)
        $successUrl = route('success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl  = route('cancel');

        $session = CheckoutSession::create([
            'client_reference_id' => auth()->id(),
            'mode' => 'subscription',

            // ✅ absolute URLs required by Stripe
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,

            'payment_method_types' => ['card'],
            'billing_address_collection' => 'required',
            'shipping_address_collection' => [
                'allowed_countries' => ['LV'],
            ],

            'line_items' => [[
                'price'    => $priceId,
                'quantity' => 1,
            ]],

            'allow_promotion_codes' => true,

            // Metadata must be strings; JSON-encode arrays/objects
            'metadata' => [
                'user_id'  => (string) auth()->id(),
                'price_id' => $priceId,
                'cart'     => is_string($cart) ? $cart : json_encode($cart),
            ],
        ]);

        return response()->json(['id' => $session->id]);
    } catch (\Throwable $e) {
        // Log full Stripe error for you; return a generic error to the client
        \Log::error('Checkout Session create failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }

    } // End of session function


    // Success and cancel views
    public function success()
    {
        return view('success');
    }

    public function cancel()
    {
        return view('cancel');
    }
}

