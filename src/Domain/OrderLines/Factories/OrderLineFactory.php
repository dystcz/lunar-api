<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Factories;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Lunar\Database\Factories\OrderLineFactory as LunarOrderLineFactory;

class OrderLineFactory extends LunarOrderLineFactory
{
    public function definition(): array
    {
        return [
            ...parent::definition(),

            'order_id' => Order::modelClass()::factory(),
        ];
    }
}
