<?php

namespace Dystcz\LunarApi\Domain\Brands\Factories;

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Lunar\Database\Factories\BrandFactory as LunarBrandFactory;

class BrandFactory extends LunarBrandFactory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
