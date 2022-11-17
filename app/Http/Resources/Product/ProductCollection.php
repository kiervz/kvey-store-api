<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\ProductImage\ProductImageResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
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
                    'id' => $request->id,
                    'sku' => $request->sku,
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'unit_price' => $request->unit_price,
                    'discount' => $request->discount,
                    'actual_price' => $request->actual_price,
                    'stock' => $request->stock,
                    'description' => $request->description,
                    'productImages' => ProductImageResource::collection($request->productImages),
                    'brand' => [
                        'name' => $request->brand->name,
                        'slug' => $request->brand->slug
                    ],
                    'category' => [
                        'name' => $request->category->name,
                        'slug' => $request->category->slug
                    ],
                    'other' => [
                        'isNew' => $request->created_at->diff(now())->d < 10 ? true : false,
                        'isSale' => $request->discount > 0
                    ]
                ];
            }),
            'meta' => [
                'total' => $this->total(),
                'page' => $this->currentPage(),
                'perPage' => (int) $this->perPage(),
                'totalPages' => $this->lastPage(),
                'path' => $this->path()
            ]
        ];
    }
}
