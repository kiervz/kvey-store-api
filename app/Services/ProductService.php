<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function showAllProducts($sort, $search, $filter)
    {
        $searchProducts = Product::where(function($query) use ($search) {
            $query->where('slug', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('sku', 'LIKE', "%$search%");
            })->where('deleted_at', null);


        if ($filter['brand']) {
            $brands = explode(',', $filter['brand']);

            $searchProducts->whereHas('brand', function($query) use ($brands) {
                $query->whereIn('brand_id', $brands);
            });
        }

        if ($filter['category']) {
            $categories = explode(',', $filter['category']);

            $searchProducts->whereHas('category', function($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }

        if ($filter['price']) {
            $priceRange = $filter['price'];

            $searchProducts->where(function($query) use ($priceRange) {
                $query->where('actual_price', '>=', $priceRange[0])
                    ->where('actual_price', '<=', $priceRange[1]);
            });
        }

        if ($sort === 'latest-arrival') {
            $searchProducts->orderBy('created_at', 'DESC');
        } else if ($sort === 'low-high') {
            $searchProducts->orderBy('actual_price', 'ASC');
        } else if ($sort === 'high-low') {
            $searchProducts->orderBy('actual_price', 'DESC');
        }

        return $searchProducts->paginate($filter['paginate'] ?? 30);
    }
}
