<?php

namespace Dystcz\LunarApi\Domain\Orders\Observers;

use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Event;
use Lunar\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            Event::dispatch(
                new OrderStatusChanged(
                    order: $order,
                    newStatus: $order->status,
                    oldStatus: $order->getOriginal('status'),
                ),
            );
        }
    }
}
