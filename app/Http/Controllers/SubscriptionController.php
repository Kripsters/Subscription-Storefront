<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\PaymentHistory;
use App\Models\Address;
use App\Models\SubscriptionOrder;
use App\Models\Subcart;
use App\Models\SubcartItem;
use App\Models\Product;
use Stripe\StripeClient;
use Tiptap\Marks\Subscript;

class SubscriptionController extends Controller
{
    protected function activeSubscription()
    {
        $subscription = Subscription::where('user_id', auth()->id())->first();
        if ($subscription && $subscription::isActiveSubscription()) {
            return true;            
        } else {
            return false;
        }
    }

    protected function activeSubcart()
    {
        // Returns active cart for current user
        return Subcart::firstOrCreate(
            ['user_id' => auth()->id()]
        );
    }

    // Display the subscription page
    public function index()
    {
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', auth()->id())->first();
        $address = Address::where('user_id', auth()->id())->first();
        
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

        if (isset($address)) {
            $billing_address = json_decode($address->billing, true);
            $shipping_address = json_decode($address->shipping, true);
            
            }

        // Pass the subscription and payments to the view
        return view('subscription.index', compact('subscription', 'payments', 'address', 'billing_address', 'shipping_address'));   
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




    public function subCart() {
        $subId = Subscription::where('user_id', auth()->id())->first();
        $existingItemsPre = SubscriptionOrder::where('subscription_id', $subId->id)->get();
        $subscriptionPrice = Subscription::where('user_id', auth()->id())->first()->amount;
        $existingItems = [];

        foreach ($existingItemsPre as $item) {
            $itemReal = Product::find($item->product_id);
            array_push($existingItems, $itemReal);
        }


        $subcartId = $this->activeSubcart()->id;
        $subcartItems = SubcartItem::where('subcart_id', $subcartId)->get();
        $subcart = [];
        $subcartSubtotal = 0;

        foreach ($subcartItems as $item) {
            $itemReal = Product::find($item->product_id);
            $itemReal->quantity = $item->quantity;
            array_push($subcart, $itemReal);
            $subcartSubtotal += $itemReal->price * $item->quantity;
        }

        $subcartIds = SubcartItem::where('subcart_id', $subcartId)->pluck('product_id');

        return view('subscription.cart', compact('existingItems', 'subcart', 'subscriptionPrice', 'subcartIds', 'subcartSubtotal'));
    }

    public function store(Request $request) {
        // Validate request
        $data = $request->validate([
            'product_id' => ['required','exists:products,id'],
            'quantity'   => ['nullable','integer','min:1']
        ]);

        // Finds or creates cart
        $subcart = $this->activeSubcart();
        $product = Product::findOrFail($data['product_id']);
        $item = SubcartItem::firstOrNew([
            'subcart_id'    => $subcart->id,
            'product_id' => $product->id,
        ]);

        // Add or update cart item
        $item->unit_price = $item->exists ? $item->unit_price : $product->price;
        $item->quantity   = ($item->exists ? $item->quantity : 0) + ($data['quantity'] ?? 1);
        $item->save();

        // Return response
        return back()->with('success','Added to cart');
    }

    public function update(Request $request, int $productId)
    {
        // Validate request
        $data = $request->validate(['quantity'=>'required|integer|min:0']);

        // Update cart
        $item = SubcartItem::where('product_id',$productId)->firstOrFail();

        // If quantity gets set to 0, remove item
        if ($data['quantity'] == 0) {
            $item->delete();
        } else {
            // Otherwise, update quantity
            $item->update(['quantity'=>$data['quantity']]);
        }

        // Return response
        return back()->with('success','Cart updated');
    }

    // Remove item
    public function destroy(int $productId)
    {
        // Get cart
        SubcartItem::where('product_id',$productId)->delete();

        // Return response
        return back()->with('success','Item removed');
    }

    public function modify() {
        $subcartId = $this->activeSubcart()->id;
        $subcartItems = SubcartItem::where('subcart_id', $subcartId)->get();
        $subId = Subscription::where('user_id', auth()->id())->first();
        $sub = Subscription::find($subId->id);

        $subcartSubtotal = 0;
        foreach ($subcartItems as $item) {
            $itemReal = Product::find($item->product_id);
            $subcartSubtotal += $itemReal->price * $item->quantity;
        }

        if ($subcartSubtotal > $sub->amount) {
            return back()->with('error','Subscription amount is not enough to cover cart');
        }

        $currentOrders = SubscriptionOrder::where('subscription_id', $sub->id)->get();
        if ($currentOrders) {
            foreach ($currentOrders as $order) {
                $order->delete();
            }
        }

        $subcartItems = SubcartItem::where('subcart_id', $subcartId)->get();
        foreach ($subcartItems as $item) {
            $order = new SubscriptionOrder;
            $order->subscription_id = $sub->id;
            $order->product_id = $item->product_id;
            $order->product_name = Product::find($item->product_id)->title;
            $order->quantity = $item->quantity;
            $order->created_at = now();
            $order->updated_at = now();
            $order->save();
        }

        SubcartItem::where('subcart_id', $subcartId)->delete();

        return back()->with('success','Subscription updated');
    }
}

