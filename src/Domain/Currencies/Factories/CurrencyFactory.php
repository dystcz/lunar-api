<?php

namespace Dystcz\LunarApi\Domain\Currencies\Factories;

use Dystcz\LunarApi\Domain\Currencies\Models\Currency;
use Lunar\Database\Factories\CurrencyFactory as LunarCurrencyFactory;

class CurrencyFactory extends LunarCurrencyFactory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->unique()->currencyCode,
            'enabled' => true,
            'exchange_rate' => $this->faker->randomFloat(2, 0.1, 5),
            'decimal_places' => 2,
            'default' => true,
        ];
    }
}
