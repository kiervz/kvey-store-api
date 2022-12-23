<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\CartItem;

class CartService
{
    public function storeCartItem($request)
    {
        $cartItem = CartItem::where([
            'user_id' => $request->user()->id,
            'product_id' => $request['product_id'],
            'status' => 'P'
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

    public function updateQtyCartItem($request)
    {
        $cartItem = CartItem::where('id', $request['cart_id'])->first();
        $action = strtoupper($request['action']);

        if ($cartItem) {
            if ($action === 'ADD') {
                $cartItem->update(['qty' => $cartItem['qty'] + ($request['total'] ?? 1)]);
            } else if ($action === 'SUBTRACT') {
                $cartItem->update(['qty' => $cartItem['qty'] - ($request['total'] ?? 1)]);
            }
        }

        return $cartItem;
    }

    public function updateStatusCartItem($request)
    {
        $cartItems = CartItem::whereIn('id', $request['cart_id'])->get();
        $status = $request['status'];

        try {
            $test = CartItem::$statusDescription[$status];
        } catch(\Exception $ex) {
            return ["error" => "Please provide valid status. Undefined status: {$status}"];
        }

        foreach ($cartItems as $item) {
            $item->update(['status' => strtoupper($request['status'])]);
        }

        return $cartItems;
    }

    public function deleteCartItem($request)
    {
        return CartItem::whereIn('id', $request['cart_item_id'])->update(['status' => 'D']);
    }
}
