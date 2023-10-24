<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Factories;

use Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\OrderLineFactory as LunarOrderLineFactory;

/**
 * @extends Factory<Model>
 */
class OrderLineFactory extends LunarOrderLineFactory
{
    protected $model = OrderLine::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),

            'order_id' => Order::factory(),
        ];
    }
}
