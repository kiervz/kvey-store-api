<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\SocialAuthController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\StripeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/auth/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('/v1/auth/me', function (Request $request) {
        return $request->user();
    });
});

Route::group(['prefix' => 'v1'], function() {
    Route::post('auth/register', [RegisterController::class, 'register'])->name('auth.register');
    Route::post('auth/login', [LoginController::class, 'login'])->name('auth.login');

    /** Login Social Auth */
    Route::get('auth/login/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('socialauth.login');
    Route::post('auth/callback/{provider}', [SocialAuthController::class, 'handleProviderCallback'])->name('socialauth.callback');

    Route::get('shop', [ShopController::class, 'showAllProducts'])->name('shop.showAllProducts');
    Route::get('shop/{product}', [ShopController::class, 'showProduct'])->name('shop.showProduct');
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['middleware' => ['auth:sanctum']], function() {
        // is admin

        Route::get('cart', [CartController::class, 'index'])->name('cart.items');
        Route::post('cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('cart/select', [CartController::class, 'selectCartItem'])->name('cart.selectCartItem');
        Route::put('cart/quantity', [CartController::class, 'updateQtyCartItem'])->name('cart.updateQtyCartItem');
        Route::put('cart/status', [CartController::class, 'updateStatusCartItem'])->name('cart.updateStatusCartItem');

        Route::post('checkout', [StripeController::class, 'checkout'])->name('checkout');
        Route::post('checkout/success', [StripeController::class, 'success'])->name('checkout.success');
    });
});
