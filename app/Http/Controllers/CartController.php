<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
class CartController extends Controller
{
    protected function activeCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active']
        )->load('items.product');
    }

    public function index()
    {
        $cart = $this->activeCart();
        foreach ($cart->items as $item) {
            $item->name = Product::find($item->product_id)->title;
        }
        $cartId = $cart->id;
        $cartItems = CartItem::where('cart_id', $cartId)->get();
        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','exists:products,id'],
            'quantity'   => ['nullable','integer','min:1']
        ]);

        $cart = $this->activeCart();
        $product = Product::findOrFail($data['product_id']);

        $item = CartItem::firstOrNew([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->unit_price = $item->exists ? $item->unit_price : $product->price;
        $item->quantity   = ($item->exists ? $item->quantity : 0) + ($data['quantity'] ?? 1);
        $item->save();

        return back()->with('success','Added to cart');
    }

    public function update(Request $request, int $productId)
    {
        $data = $request->validate(['quantity'=>'required|integer|min:0']);
        // dd($data['quantity']);

        $cart = $this->activeCart();
        $item = $cart->items()->where('product_id',$productId)->firstOrFail();

        if ($data['quantity'] == 0) {
            $item->delete();

        } else {
            $item->update(['quantity'=>$data['quantity']]);
        }

        return back()->with('success','Cart updated');
    }

    public function destroy(int $productId)
    {
        $cart = $this->activeCart();
        $cart->items()->where('product_id',$productId)->delete();

        return back()->with('success','Item removed');
    }

    public function clear()
    {
        $cart = $this->activeCart();
        $cart->items()->delete();

        return back()->with('success','Cart cleared');
    }
}
