<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/about', function () {
    return view('about');
})->middleware(['auth', 'verified'])->name('about');

//admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    });

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // Add more admin routes here
});


Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/billing', [ProfileController::class, 'billingUpdate'])->name('profile.billingUpdate');
    Route::patch('/profile/shipping', [ProfileController::class, 'shippingUpdate'])->name('profile.shippingUpdate');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/search' , [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/show/{id}' , [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart',                [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/items',         [CartController::class,'store'])->name('cart.add');
    Route::patch('/cart/items/{id}',   [CartController::class,'update'])->name('cart.update');
    Route::delete('/cart/items/{id}',  [CartController::class,'destroy'])->name('cart.remove');
    Route::delete('/cart',             [CartController::class,'clear'])->name('cart.clear');

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/pause', [SubscriptionController::class, 'pause'])->name('subscription.pause');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::post('/subscription/update-products', [SubscriptionController::class, 'updateProducts'])->name('subscription.updateProducts');
    Route::get('/subscription/cart', [SubscriptionController::class, 'subCart'])->name('subscription.cart');
    Route::post('/subscription/items',         [SubscriptionController::class,'store'])->name('subscription.add');
    Route::patch('/subcart/items/{id}',   [SubscriptionController::class,'update'])->name('subcart.update');
    Route::delete('/subcart/items/{id}',  [SubscriptionController::class,'destroy'])->name('subcart.remove');
    Route::post('/subcart/save', [SubscriptionController::class,'modify'])->name('subcart.modify');
    

    Route::get('/subscribe', [PaymentController::class, 'subscribe'])->name('subscribe');
    Route::post('/create-subscription-session', [PaymentController::class, 'session'])->name('subscription.session');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');

    
    Route::get('/subscriptions/{subscription}/edit-items', [CartController::class, 'editSubscriptionItems'])
        ->name('subscriptions.edit-items');

    Route::post('/subscriptions/{subscription}/apply-items', [CartController::class, 'applySubscriptionItems'])
        ->name('subscriptions.apply-items');


    
    Route::get('/test-email', function () {
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('penguingaming113@gmail.com')
                    ->subject('Test Email');
        });

        return 'Email sent!';
    });

});

require __DIR__.'/auth.php';
