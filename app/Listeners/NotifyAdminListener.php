<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Mail\Message;
use Mail;

class NotifyAdminListener
{

    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;
        Mail::send('admin', ['order' => $order], function (Message $message) {
            $message->to('admin@admin.com');
            $message->subject('A new order has been completed');
        });
    }
}
