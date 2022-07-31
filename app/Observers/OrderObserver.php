<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order)
    {

        $originalOrder = new Order(collect($order->getOriginal())->only($order->statuses)->toArray());
        $filledStatuses = collect($order->getDirty())->only($order->statuses)->filter(fn ($status) => filled($status));

        if ($originalOrder->status() !== $order->status() && $filledStatuses->count()) {
            // send email
            Mail::to($order->user)->send(new OrderStatusUpdated($order));
        }
    }
}
