<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
class PaymentController extends Controller
{
    public function subscribe()
    {
        return view('subscribe');
    }

    public function session(Request $request)
    {
        
        try{
        $cart = $request->input('cart');
        $plan = $request->input('plan');
        Stripe::setApiKey(env('STRIPE_SECRET'));
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

        $session = Session::create([
            'payment_method_types' => ['card'],
                // request billing address
            'billing_address_collection' => 'required',

                // request shipping address
            'shipping_address_collection' => [
                'allowed_countries' => ['LV'], // pick the countries you support
            ],

            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),
            'metadata' => [
                'user_id' => auth()->id(),
                'price_id' => $priceId,
                'cart' => $cart,
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

