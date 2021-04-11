<?php

namespace App\Http\Controllers\Influencer;

use App\Link;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Log;

class StatsController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->where('complete', 1)->get();
            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => round($orders->sum(function (Order $order) {
                    return $order->influencer_total;
                }), 2)
            ];
        });
    }


    public function rankings()
    {
        // return \Cache::get('rankings');
        // return Redis::zrange('rankings', 0, -1, 'WITHSCORES');
        return Redis::zrange('rankings', 0, -1);
    }
}
