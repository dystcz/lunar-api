<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Factories;

use Dystcz\LunarApi\Domain\ProductOptionValues\Models\ProductOptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\ProductOptionValueFactory as LunarProductOptionValueFactory;

/**
 * @extends Factory<Model>
 */
class ProductOptionValueFactory extends LunarProductOptionValueFactory
{
    protected $model = ProductOptionValue::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
