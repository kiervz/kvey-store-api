<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($request) {
                return [
                    'sku' => $request->product->sku,
                    'name' => $request->product->name,
                    'slug' => $request->product->slug,
                    'brand' => $request->product->brand->name,
                    'price' => $request->product->actual_price,
                    'qty' => $request->qty,
                    'sub_total' => $request->product->actual_price * $request->qty,
                    'stock' => $request->product->stock,
                    'selected' => $request->selected,
                    'image' => $request->product->productImages[0]->url
                ];
            }),
        ];
    }
}
