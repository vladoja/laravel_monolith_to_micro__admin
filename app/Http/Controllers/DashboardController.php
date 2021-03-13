<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chart()
    {
        $orders = Order::query()->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw("DATE_FORMAT(orders.created_at,'%Y-%m-%d') as date, sum(order_items.price*order_items.quantity) as sum")
            ->groupBy('date')
            ->get();

        return $orders;
    }
}
