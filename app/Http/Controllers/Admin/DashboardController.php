<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ChartResource;
use App\Order;

class DashboardController
{
    public function chart()
    {
        \Gate::authorize('view', 'orders');
        $orders = Order::query()->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at,'%Y-%m-%d') as date, sum(order_items.price*order_items.quantity) as sum")
            ->groupBy('date')
            ->get();

        return ChartResource::collection($orders);
    }
}
