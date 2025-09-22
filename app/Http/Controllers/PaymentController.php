<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
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
        
        try{ // Create a Stripe Checkout Session | The entire function is wrapped in a try/catch block to handle errors
        // Get the cart and plan
        $cart = $request->input('cart');
        $plan = $request->input('plan');

        // Set your Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get the price ID based on the selected plan
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

        // Create the Stripe Checkout Session
        $session = Session::create([
            
            'payment_method_types' => ['card'],

            // Addresses
            'billing_address_collection' => 'required',
            'shipping_address_collection' => [
                'allowed_countries' => ['LV'],
            ],

            // Line items
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],

            // Payment
            'mode' => 'subscription',
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),
            'metadata' => [
                // Metadata
                'user_id' => auth()->id(),
                'price_id' => $priceId,
                'cart' => $cart,
            ],
            // Promo Codes allowed
            'allow_promotion_codes' => true,
        ]);

        // Return the session ID
        return response()->json(['id' => $session->id]);


        } catch (\Exception $e) { // Catch exceptions
            // Handle errors by returning a JSON response
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

