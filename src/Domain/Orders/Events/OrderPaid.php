<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Lunar\Models\Order;

class OrderPaid
{
    use Dispatchable;

    public function __construct(Order $order)
    {
    }
}
