<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['guestOrVerified'])->group(function (){
    Route::get('/',[ProductController::class, 'index'])->name('home');
    Route::get('/product/{product:slug}',[ProductController::class, 'view'])->name('product.view');

    Route::prefix('/cart')->name('cart.')->group(function (){
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
});



Route::get('/dashboard', function () {
   return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
});

Route::controller(PaymentController::class)
    ->prefix('payments')
    ->as('payments')
    // ->name('payment.')
    ->group(function(){
        // Route::get('/token', [PaymentController::class, 'token'])->name('token');
        Route::get('/initiate-push',  'initiateStkPush')->name('intiate');
        Route::post('/stkcallback',  'stkCallback')->name('stkcallback');
        // Route::get('/success', [PaymentController::class, 'success'])->name('success');
        // Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    })
    ->middleware(['auth', 'verified']);


require __DIR__.'/auth.php';
