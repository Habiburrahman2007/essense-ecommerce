<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\ProductDetail;
use App\Livewire\MyAccount;
use App\Livewire\Cart;
use App\Livewire\PaymentSuccess;
use App\Livewire\PaymentFailed;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');
    Route::get('/my-account', MyAccount::class)->name('my-account');
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/orders', App\Livewire\MyOrders::class)->name('my-orders');
    
    // Payment Routes
    Route::get('/payment/success/{orderId}', PaymentSuccess::class)->name('payment.success');
    Route::get('/payment/failed/{orderId}', PaymentFailed::class)->name('payment.failed');
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Midtrans Callback Routes (tidak perlu auth)
Route::post('/midtrans/callback', [App\Http\Controllers\MidtransCallbackController::class, 'handle'])->name('midtrans.callback');
Route::get('/midtrans/finish', [App\Http\Controllers\MidtransCallbackController::class, 'finish'])->name('midtrans.finish');
