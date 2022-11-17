<?php

namespace App\Services;

use App\Models\CartItem;

class CartService
{
    public function storeCartItem($request)
    {
        $cartItem = CartItem::where([
            'user_id' => $request->user()->id,
            'product_id' => $request['product_id']
        ])->first();

        if ($cartItem) {
            $cartItem->update(['qty' => $cartItem['qty'] += $request['qty']]);
        } else {
            $cartItem = CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $request['product_id'],
                'qty' => $request['qty']
            ]);
        }

        return $cartItem;
    }

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
