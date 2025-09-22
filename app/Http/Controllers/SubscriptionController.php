<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\PaymentHistory;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    // Display the subscription page
    public function index()
    {
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->first();
        
        // Check if there are any payment histories for the user
        if (PaymentHistory::where('user_id', auth()->id())->exists()) {
            // Fetch all payment histories for the user
            $payments = PaymentHistory::where('user_id', auth()->id())
                // Get the latest payments first
                ->orderBy('paid_at', 'desc')
                ->get();
        } else {
            // If no payment histories exist, set payments to an empty array
            $payments = [];
        }

        // Pass the subscription and payments to the view
        return view('subscription.index', compact('subscription', 'payments'));
    }



    // Cancel a user's subscription
    public function cancel(Request $request)
    {
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        // Update the Stripe subscription to cancel at the end of the billing period
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'cancel_at_period_end' => true,
        ]);

        // Update the subscription status in the database
        $subscription->status = 'canceled';
        $subscription->save();

        // Redirect back with a success message
        return back()->with('status', 'Subscription will be canceled at the end of this billing period.');
    }




     // Pause a user's subscription
    public function pause(Request $request)
    {
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        // Update the Stripe subscription to pause collection
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'pause_collection' => ['behavior' => 'mark_uncollectible'],
        ]);

        // Update the subscription status in the database
        $subscription->status = 'paused';
        $subscription->save();

        // Redirect back with a success message
        return back()->with('status', 'Subscription has been paused.');
    }



    // Resume a user's subscription

    public function resume(Request $request)
    {
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        // Update the Stripe subscription to cancel pause collection
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'pause_collection' => '',
        ]);

        // Update the subscription status in the database
        $subscription->status = 'active';
        $subscription->save();

        // Redirect back with a success message
        return back()->with('status', 'Subscription has been resumed.');
    }



    // Update the products for a user's subscription

    public function updateProducts(Request $request)
    {
        // Validate the request
        $request->validate([
            'products' => 'required|array',
        ]);

        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        // Store the chosen products in your own DB
        $subscription->products = json_encode($request->products);
        $subscription->save();

        // Redirect back with a success message
        return back()->with('status', 'Products updated successfully.');
    }
}

