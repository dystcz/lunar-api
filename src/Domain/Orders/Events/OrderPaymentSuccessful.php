<?php

namespace Dystcz\LunarApi\Domain\Orders\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Lunar\Models\Order;

class OrderPaymentSuccessful
{
    use Dispatchable;

    public function __construct(
        public Order $order,
    ) {
    }
}
