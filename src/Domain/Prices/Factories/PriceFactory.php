<?php

namespace Dystcz\LunarApi\Domain\Prices\Factories;

use Dystcz\LunarApi\Domain\Currencies\Models\Currency;

class PriceFactory extends \Lunar\Database\Factories\PriceFactory
{
    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(1, 2500),
            'compare_price' => $this->faker->numberBetween(1, 2500),
            'currency_id' => Currency::modelClass()::getDefault() ?? Currency::modelClass()::factory(),
        ];
    }
}
