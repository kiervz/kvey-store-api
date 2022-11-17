<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\ProductImage\ProductImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'unit_price' => $this->unit_price,
            'discount' => $this->discount,
            'actual_price' => $this->actual_price,
            'stock' => $this->stock,
            'description' => $this->description,
            'productImages' => ProductImageResource::collection($this->productImages),
            'brand' => [
                'name' => $this->brand->name,
                'slug' => $this->brand->slug
            ],
            'category' => [
                'name' => $this->category->name,
                'slug' => $this->category->slug
            ]
        ];
    }
}
