<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display all products
     public function index(Request $request)
     {
         $perPage = $request->integer('per_page', 12); // default page size
 
         $products = Product::query()
             ->orderBy('title', 'asc') // or created_at desc
             ->paginate($perPage)
             ->withQueryString();      // keep ?per_page etc. in pagination links

         foreach ($products as $product) {
            if (Category::find($product->category_id) == null) {
                $product->category = 'Uncategorized';
            } else {
                $product->category = Category::find($product->category_id)->name;
            }
         }

         $isActive = Subscription::isActiveSubscription();
         $categories = Category::orderBy('name')->get();

         return view('products.index', compact('products', 'isActive', 'categories'));
     }



     // Process search request
     public function search(Request $request)
     {
         // Validate the request
         $validated = $request->validate([
             'search'      => 'nullable|string|max:100',
             'order'       => 'nullable|in:asc,desc,price_asc,price_desc',
             'per_page'    => 'nullable|integer|min:1|max:100',
             'category_id' => 'nullable|integer|exists:categories,id',
         ]);

         // Get the search term, order, per_page, and category
         $term       = $validated['search'] ?? null;
         $order      = $validated['order'] ?? null;
         $perPage    = $validated['per_page'] ?? 12;
         $categoryId = $validated['category_id'] ?? null;

         // Build the query
         $builder = Product::query();

         // Apply the search term
         if ($term) {
             $builder->where(function ($q) use ($term) {
                 $q->where('title', 'LIKE', "%{$term}%")
                   ->orWhere('description', 'LIKE', "%{$term}%");
             });
         }

         // Apply the category filter
         if ($categoryId) {
             $builder->where('category_id', $categoryId);
         }

         // Apply the order
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

         $isActive   = Subscription::isActiveSubscription();
         $categories = Category::orderBy('name')->get();

         foreach ($products as $product) {
             if (Category::find($product->category_id) == null) {
                 $product->category = 'Uncategorized';
             } else {
                 $product->category = Category::find($product->category_id)->name;
             }
         }

         return view('products.index', compact('products', 'isActive', 'categories'));
     }

    // Route to show a specific product
    public function show($id)
    {
        $isActive = Subscription::isActiveSubscription();
        
        $product = Product::find($id);

        if (Category::find($product->category_id) == null) {
            $product->category = 'Uncategorized';
        } else {
            $product->category = Category::find($product->category_id)->name;
        }

        return view('products.show', compact('product', 'isActive'));
    }

}
