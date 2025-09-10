<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Checkout\Session;
class PaymentController extends Controller
{
    public function subscribe()
    {
        return view('subscribe');
    }

    public function session(Request $request)
    {
        try{
        $cartItems = $request->input('cart', []);
        $cartJson = json_encode($cartItems);
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
                // request billing address
            'billing_address_collection' => 'required',

                // request shipping address
            'shipping_address_collection' => [
                'allowed_countries' => ['LV'], // pick the countries you support
            ],

            'line_items' => [[
                'price' => env('STRIPE_SUBSCRIPTION_PRICE_BASIC'),
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),

            'metadata' => [
                'user_id' => auth()->id(),
                'price_id' => env('STRIPE_SUBSCRIPTION_BASIC'),
                'cart' => $cartJson,
            ],        
        ]);

        return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success()
    {
        return view('success');
    }

    public function cancel()
    {
        return view('cancel');
    }
}

