<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\PaymentHistory;
use Faker\Provider\ar_EG\Payment;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
        $subscription = Subscription::where('user_id', auth()->id())->first();
         
        if (PaymentHistory::where('user_id', auth()->id())->exists()) {
            $payments = PaymentHistory::where('user_id', auth()->id())
            ->orderBy('paid_at', 'desc')
            ->get();
        } else {
            $payments = [];
        }

         return view('subscription.index', compact('subscription', 'payments'));
     }

     public function cancel(Request $request)
    {
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'cancel_at_period_end' => true,
        ]);

        $subscription->status = 'canceled';
        $subscription->save();

        return back()->with('status', 'Subscription will be canceled at the end of this billing period.');
    }

    public function pause(Request $request)
    {
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'pause_collection' => ['behavior' => 'mark_uncollectible'],
        ]);

        $subscription->status = 'paused';
        $subscription->save();

        return back()->with('status', 'Subscription has been paused.');
    }

    public function resume(Request $request)
    {
        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $stripe->subscriptions->update($subscription->stripe_subscription_id, [
            'pause_collection' => '',
        ]);

        $subscription->status = 'active';
        $subscription->save();

        return back()->with('status', 'Subscription has been resumed.');
    }

    public function updateProducts(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $subscription = Subscription::where('user_id', auth()->id())->firstOrFail();

        // Store the chosen products in your own DB
        $subscription->products = json_encode($request->products);
        $subscription->save();

        return back()->with('status', 'Products updated successfully.');
    }


}
