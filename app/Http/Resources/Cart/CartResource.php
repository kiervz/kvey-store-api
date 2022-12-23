<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->product->sku,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'brand' => $this->product->brand->name,
            'unit_price' => $this->product->unit_price,
            'price' => $this->product->actual_price,
            'discount' => $this->product->discount,
            'qty' => $this->qty,
            'sub_total' => $this->product->actual_price * $this->qty,
            'stock' => $this->product->stock,
            'selected' => $this->selected,
            'image' => $this->product->productImages[0]->url
        ];
    }
}
