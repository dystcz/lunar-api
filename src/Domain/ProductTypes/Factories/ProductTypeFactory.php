<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\Factories;

use Dystcz\LunarApi\Domain\ProductTypes\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\ProductTypeFactory as LunarProductTypeFactory;

/**
 * @extends Factory<Model>
 */
class ProductTypeFactory extends LunarProductTypeFactory
{
    protected $model = ProductType::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
