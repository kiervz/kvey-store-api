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
            $brandName = $filter['brand'];

            $searchProducts->whereHas('brand', function($query) use ($brandName) {
                $query->where('slug', $brandName);
            });
        }

        if ($filter['category']) {
            $categoryName = $filter['category'];

            $searchProducts->whereHas('category', function($query) use ($categoryName) {
                $query->where('slug', $categoryName);
            });
        }

        if ($filter['price']) {
            $priceRange = explode('-', $filter['price']);

            $searchProducts->where(function($query) use ($priceRange) {
                $query->where('actual_price', '>=', $priceRange[0])
                    ->where('actual_price', '<=', $priceRange[1]);
            });
        }

        if ($sort === 'latest') {
            $searchProducts->orderBy('created_at', 'DESC');
        } else if ($sort === 'lowest-price') {
            $searchProducts->orderBy('actual_price', 'ASC');
        } else if ($sort === 'highest-price') {
            $searchProducts->orderBy('actual_price', 'DESC');
        }

        return $searchProducts->paginate(30);
    }
}
