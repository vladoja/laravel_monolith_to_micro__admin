<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsListener
{
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        $revenue = $order->influencer_total;

        $user = User::find($order->user_id);

        Redis::zincby('rankings', $revenue, $user->full_name);
    }
}
