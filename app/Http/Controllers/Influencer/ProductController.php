<?php

namespace App\Http\Controllers\Influencer;

use App\Product;

class ProductController
{
    public function index()
    {
        return Product::all();
    }
}
