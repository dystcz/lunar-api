<?php

namespace Dystcz\LunarApi\Domain\Orders\Observers;

use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Event;
use Lunar\Models\Contracts\Order as OrderContract;
use Lunar\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     */
    public function updating(OrderContract $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(OrderContract $order): void
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
