<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Resources\ProductResource;
use App\Product;
use Cache;
use Illuminate\Http\Request;

class ProductController
{
    public function index(Request $request)
    {
        $result = Cache::get('products');
        if ($result) {
            return $result;
        }
        sleep(2);
        $query = Product::query();
        if ($s = $request->input('s')) {
            $query->whereRaw("title LIKE '%{$s}%'")
                ->orWhereRaw("description LIKE '%{$s}%'");
        }

        $response = ProductResource::collection($query->get());
        Cache::set('products', $response, 5);
        return $response;
    }
}
