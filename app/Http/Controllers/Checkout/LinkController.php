<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Link;
use Illuminate\Http\Request;

class LinkController
{
    public function show($code)
    {
        // Dva sposoby, ako ziskat link cez code
        // $link = Link::where('code', $code);
        // $link = Link::whereCode($code);
        $link = Link::where('code', $code)->first();

        return new LinkResource($link);
    }
}
