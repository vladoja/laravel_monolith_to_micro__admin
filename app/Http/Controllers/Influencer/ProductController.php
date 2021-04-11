<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Resources\ProductResource;
use App\Product;
use Cache;
use Illuminate\Http\Request;
use Str;

class ProductController
{
    public function index(Request $request)
    {
        $products = Cache::remember('products', 30 * 60, function () use ($request){
            sleep(2);
            return Product::all();
        });

        if ($s = $request->input('s')) {
            $products = $products->filter(function (Product $product) use ($s) {
                return Str::contains($product->title, $s) || Str::contains($product->description, $s);
            });
        }

        return ProductResource::collection($products);
    }
}
