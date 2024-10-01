<?php

namespace Dystcz\LunarApi\Domain\Customers\Factories;

use Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;

class CustomerFactory extends \Lunar\Database\Factories\CustomerFactory
{
    public function withOrder(): static
    {
        return $this->has(
            Order::factory()
                ->has(
                    OrderLine::factory()
                        ->for(ProductVariant::factory()->withPrice(), 'purchasable'),
                    'lines'
                )
        );
    }
}
