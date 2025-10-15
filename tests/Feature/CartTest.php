<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs, post, assertDatabaseHas, assertDatabaseCount};
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('validates the request data', function () {
    post(route('cart.add'), [])
        ->assertSessionHasErrors(['product_id']);
});

it('fails if product does not exist', function () {
    post(route('cart.add'), [
        'product_id' => 999999,
        'quantity' => 1,
    ])->assertSessionHasErrors(['product_id']);
});

it('creates a new cart item if it does not exist', function () {
    $product = Product::factory()->create(['price' => 1000]);

    post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertSessionHas('success', 'Added to cart');

    assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 1000,
    ]);
});

it('increments quantity if the product already exists in cart', function () {
    $product = Product::factory()->create(['price' => 500]);
    $user = User::factory()->create();
    actingAs($user);
    post(route('cart.add'), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertSessionHas('success', 'Added to cart');
    $cart = Cart::updateOrCreate([
        'user_id' => $user->id,
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $item = CartItem::updateOrCreate([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'unit_price' => $product->price,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user)->patch(route('cart.update', $product->id), [
        'quantity' => 3,
    ]);

    $item->refresh();
    expect($item->quantity)->toBe(3);
});
