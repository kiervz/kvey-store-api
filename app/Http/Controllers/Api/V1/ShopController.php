<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;

use App\Models\Product;

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

        return $this->customResponse('success', new ProductCollection($products));
    }

    public function showProduct(Product $product)
    {
        return $this->customResponse('success', new ProductResource($product));
    }
}
