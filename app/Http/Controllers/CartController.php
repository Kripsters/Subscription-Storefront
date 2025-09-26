<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Price;
use Tiptap\Marks\Subscript;

class CartController extends Controller
{
    // Get active cart

    protected function activeCart()
    {
        // Returns active cart for current user
        return Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active']
        )->load('items.product');
    }


    // Show cart
    public function index()
    {
        // Get active cart
        $cart = $this->activeCart();
        // Grab each cart item
        foreach ($cart->items as $item) {
            $item->name = Product::find($item->product_id)->title;
        }
        $cartId = $cart->id;
        $cartItems = CartItem::where('cart_id', $cartId)->get();
        $prices = Price::all();

        $cart->items->each->makeHidden(['product']);

        $subscription = Subscription::where('user_id', auth()->id())->get();
        if ($subscription->isEmpty() || $subscription[0]->status != 'active') {        
            $allowed = true;
        } else {
            $allowed = false;
        }

        // Return view
        return view('cart.index', compact('cart','prices', 'cartItems', 'allowed'));
    }


    // Adds product to cart
    public function store(Request $request)
    {
        // Validate request
        $data = $request->validate([
            'product_id' => ['required','exists:products,id'],
            'quantity'   => ['nullable','integer','min:1']
        ]);

        // Finds or creates cart
        $cart = $this->activeCart();
        $product = Product::findOrFail($data['product_id']);
        $item = CartItem::firstOrNew([
            'cart_id'    => $cart->id,
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
        $cart = $this->activeCart();
        $item = $cart->items()->where('product_id',$productId)->firstOrFail();

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
        $cart = $this->activeCart();
        // Remove item
        $cart->items()->where('product_id',$productId)->delete();

        // Return response
        return back()->with('success','Item removed');
    }


    // Clear cart
    public function clear()
    {
        // Get cart
        $cart = $this->activeCart();
        // Delete items
        $cart->items()->delete();


        // Return response
        return back()->with('success','Cart cleared');
    }
}
