<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\SocialAuthController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\StripeController;
use App\Http\Resources\User\UserResource;

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
    Route::group(['prefix' => 'v1/auth'], function() {
        Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
        Route::get('me', function (Request $request) {
            return new UserResource($request->user());
        });
    });
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [RegisterController::class, 'register'])->name('auth.register');
        Route::post('login', [LoginController::class, 'login'])->name('auth.login');

        /** Login Social Auth */
        Route::get('login/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('socialauth.login');
        Route::post('callback/{provider}', [SocialAuthController::class, 'handleProviderCallback'])->name('socialauth.callback');
    });

    Route::get('shop', [ShopController::class, 'showAllProducts'])->name('shop.showAllProducts');
    Route::get('shop/{product}', [ShopController::class, 'showProduct'])->name('shop.showProduct');
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['middleware' => ['auth:sanctum']], function() {
        /** is admin */

        Route::get('cart', [CartController::class, 'index'])->name('cart.items');
        Route::post('cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('cart/select', [CartController::class, 'selectCartItem'])->name('cart.selectCartItem');
        Route::put('cart/quantity', [CartController::class, 'updateQtyCartItem'])->name('cart.updateQtyCartItem');
        Route::put('cart/status', [CartController::class, 'updateStatusCartItem'])->name('cart.updateStatusCartItem');

        Route::post('checkout', [StripeController::class, 'checkout'])->name('checkout');
        Route::post('checkout/success', [StripeController::class, 'success'])->name('checkout.success');
    });
});
