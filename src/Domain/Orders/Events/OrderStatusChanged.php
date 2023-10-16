<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Dystcz\LunarApi\Domain\Orders\Contracts\OrderStatusContract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lunar\Models\Order;

class OrderStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Order $order,
        // public OrderStatusContract $oldStatus,
        // public OrderStatusContract $newStatus,
    ) {
    }
}
