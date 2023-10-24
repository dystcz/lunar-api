<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Factories;

use Dystcz\LunarApi\Domain\ProductOptions\Models\ProductOption;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\ProductOptionFactory as LunarProductOptionFactory;

/**
 * @extends Factory<Model>
 */
class ProductOptionFactory extends LunarProductOptionFactory
{
    protected $model = ProductOption::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
