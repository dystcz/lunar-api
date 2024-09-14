<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lunar\Models\Contracts\Order as OrderContract;

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
        public OrderContract $order,
        public string $newStatus,
        public string $oldStatus,
    ) {}
}
