<?php

namespace Dystcz\LunarApi\Domain\Orders\Factories;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Database\Factories\OrderFactory as LunarOrderFactory;

class OrderFactory extends LunarOrderFactory
{
    protected $model = Order::class;
}
