<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Order;

class OrderController
{
    public function index()
    {
        \Gate::authorize('view', 'orders');
        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        \Gate::authorize('view', 'orders');
        return new OrderResource(Order::find($id));
    }

    public function export()
    {
        $file_name = "orders.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () {
            $orders = Order::all();
            $file = fopen('php://output', 'w');
            // Header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Product title', 'Price', 'Quantity']);

            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);
                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }
            fclose($file);
        };
        return \Response::stream($callback, 200, $headers);
    }
}
