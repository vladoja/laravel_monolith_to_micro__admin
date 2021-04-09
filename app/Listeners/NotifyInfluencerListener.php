<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Mail\Message;
use Mail;

class NotifyInfluencerListener
{

    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;
        Mail::send('influencer', ['order' => $order], function (Message $message) use ($order) {
            $message->to($order->influencer_email);
            $message->subject('A new order has been completed');
        });
    }
}
