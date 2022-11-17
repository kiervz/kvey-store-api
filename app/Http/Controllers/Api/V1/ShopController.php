<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ShopController extends Controller
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function showAllProducts(Request $request)
    {
        $sort = $request->get('sort');
        $search = $request->get('q');

        $filter = [
            'brand' => $request->get('brand'),
            'category' => $request->get('category'),
            'price' => $request->get('price'),
        ];

        $products = $this->productService->showAllProducts($sort, $search, $filter);

        return $this->customResponse('results', $products);
    }
}
