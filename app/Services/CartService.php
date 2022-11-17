<?php

namespace App\Services;

use App\Models\CartItem;

class CartService
{
    public function selectCart($request)
    {
        $cartItem = CartItem::where('id', $request['cart_id'])->first();

        $cartItem->update(['selected' => $request['is_selected']]);

        return $cartItem;
    }

    public function updateCartItem($request)
    {
        $cartItem = CartItem::where('id', $request['cart_id'])->first();

        if ($cartItem) {
            if ($request['action'] === 'ADD') {
                $cartItem->update(['qty' => $cartItem['qty'] + 1]);
            } else if ($request['action'] === 'SUBTRACT') {
                $cartItem->update(['qty' => $cartItem['qty'] - 1]);
            }
        }

        return $cartItem;
    }
}
