<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         $perPage = $request->integer('per_page', 12); // default page size
 
         $products = Product::query()
             ->orderBy('title', 'asc') // or created_at desc
             ->paginate($perPage)
             ->withQueryString();      // keep ?per_page etc. in pagination links
 
         return view('products.index', compact('products'));
     }
 


     public function search(Request $request)
     {
         $validated = $request->validate([
             'search'   => 'nullable|string|max:100',
             'order'    => 'nullable|in:asc,desc,price_asc,price_desc',
             'per_page' => 'nullable|integer|min:1|max:100',
         ]);
 
         $term    = $validated['search'] ?? null;
         $order   = $validated['order'] ?? null;
         $perPage = $validated['per_page'] ?? 12;
 
         $builder = Product::query();
 
         if ($term) {
             $builder->where(function ($q) use ($term) {
                 $q->where('title', 'LIKE', "%{$term}%")
                   ->orWhere('description', 'LIKE', "%{$term}%");
             });
         }
 
         if ($order) {
             switch ($order) {
                 case 'price_asc':
                     $builder->orderBy('price', 'asc');
                     break;
                 case 'price_desc':
                     $builder->orderBy('price', 'desc');
                     break;
                 default:
                     $builder->orderBy('title', $order);
             }
         }
 
         $products = $builder
             ->paginate($perPage)
             ->withQueryString();
 
         return view('products.index', compact('products'));
     }
 

    public function addToCart($id)
    {
        $productId = $id;
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        // Add product to cart
        $cart = session()->get('cart', []);
        $cart[$productId] = [
            'name' => $product->title,
            'price' => $product->price,
        ];
        session()->put('cart', $cart);
        dd(session()->all());
        return redirect()->route('products.index')->with('success', 'Product added to cart successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
