<?php

namespace Dystcz\LunarApi\Domain\Orders\Observers;

use Dystcz\LunarApi\Domain\Orders\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Event;
use Lunar\Models\Order;
use Lunar\Observers\OrderObserver as LunarOrderObserver;

class OrderObserver extends LunarOrderObserver
{
    /**
     * Handle the OrderLine "updated" event.
     */
    public function updating(Order $order): void
    {
        if ($order->isDirty('status')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($order)
                ->event('status-update')
                ->withProperties([
                    'new' => $order->status,
                    'previous' => $order->getOriginal('status'),
                ])->log('status-update');

            Event::dispatch(
                new OrderStatusChanged(
                    order: $order,
                    newStatus: $order->status,
                    oldStatus: $order->getOriginal('status'),
                ),
            );
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }
}
