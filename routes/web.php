<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
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


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/search' , [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/show/{id}' , [ProductController::class, 'show'])->name('products.show');

    Route::get('/cart',                [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/items',         [CartController::class,'store'])->name('cart.add');
    Route::patch('/cart/items/{id}',   [CartController::class,'update'])->name('cart.update');
    Route::delete('/cart/items/{id}',  [CartController::class,'destroy'])->name('cart.remove');
    Route::delete('/cart',             [CartController::class,'clear'])->name('cart.clear');

    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    

    Route::get('/subscribe', [PaymentController::class, 'subscribe'])->name('subscribe');
    Route::post('/create-subscription-session', [PaymentController::class, 'session'])->name('subscription.session');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');

    
    Route::get('/test-email', function () {
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('penguingaming113@gmail.com')
                    ->subject('Test Email');
        });

        return 'Email sent!';
    });

});

require __DIR__.'/auth.php';
