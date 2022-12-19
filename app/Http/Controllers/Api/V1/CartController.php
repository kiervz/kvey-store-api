<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\Cart\CartCollection;
use App\Http\Resources\Cart\CartResource;
use App\Services\CartService;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartSelectRequest;
use App\Http\Requests\Cart\CartUpdateQtyRequest;
use App\Http\Requests\Cart\CartUpdateStatusRequest;
use App\Http\Requests\Cart\CartDeleteRequest;

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

    public function store(CartStoreRequest $request)
    {
        $cartItem = $this->cartService->storeCartItem($request);

        return $this->customResponse('Product successfully added to your cart!', new CartResource($cartItem));
    }

    public function selectCartItem(CartSelectRequest $request)
    {
        $cartItem = $this->cartService->selectCart($request);

        return $this->customResponse('success', new CartResource($cartItem));
    }

    public function updateQtyCartItem(CartUpdateQtyRequest $request)
    {
        $cartItem = $this->cartService->updateQtyCartItem($request);

        return $this->customResponse('success', new CartResource($cartItem));
    }

    public function updateStatusCartItem(CartUpdateStatusRequest $request)
    {
        $cartItems = $this->cartService->updateStatusCartItem($request);

        if (@$cartItems['error']) {
            return $this->customResponse($cartItems['error'], [], Response::HTTP_BAD_REQUEST, false);
        }

        return $this->customResponse('success', new CartCollection($cartItems));
    }

    public function destroy(CartDeleteRequest $request)
    {
        $this->cartService->deleteCartItem($request);

        return $this->customResponse('Cart item deleted successfully!', [], Response::HTTP_NO_CONTENT);
    }
}
