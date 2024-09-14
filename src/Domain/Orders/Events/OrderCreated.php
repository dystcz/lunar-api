<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Lunar\Models\Contracts\Order as OrderContract;

class OrderCreated
{
    use Dispatchable;

    public function __construct(
        public OrderContract $order,
    ) {}
}
