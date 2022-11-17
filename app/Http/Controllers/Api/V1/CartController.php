<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Cart\CartCollection;
use App\Http\Resources\Cart\CartResource;
use App\Services\CartService;

use Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return $this->customResponse('success', new CartCollection(Auth::user()->cartItems));
    }

    public function selectCartItem(Request $request)
    {
        $cartItem = $this->cartService->selectCart($request);

        return $this->customResponse('success', new CartResource($cartItem));
    }

    public function updateCartItem(Request $request)
    {
        $cartItem = $this->cartService->updateCartItem($request);

        return $this->customResponse('success', new CartResource($cartItem));
    }
}
