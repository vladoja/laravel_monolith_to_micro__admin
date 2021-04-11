<?php

namespace App\Listeners;

use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class ProductCacheFlush
{
    public function handle($event)
    {
        Cache::forget('products');
    }
}
