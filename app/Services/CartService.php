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
}
