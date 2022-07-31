<?php

namespace App\Models\Presenters;

use App\Models\Order;


class OrderPresenter
{
    public function __construct(protected Order $order)
    {
    }

    public function status()
    {
        return match ($this->order->status()) {
            'placed_at' => 'Order placed',
            'shipped_at' => 'Order shipped',
            'packaged_at' => 'Order packaged',
            'default' => ''
        };
    }
}
