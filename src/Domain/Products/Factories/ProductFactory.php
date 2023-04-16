<?php

namespace Dystcz\LunarApi\Domain\Products\Factories;

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Lunar\Database\Factories\ProductFactory as LunarProductFactory;
use Lunar\FieldTypes\Text;
use Lunar\Models\ProductType;

class ProductFactory extends LunarProductFactory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_type_id' => ProductType::factory(),
            'status' => 'published',
            'brand_id' => Brand::factory()->create()->id,
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
                'description' => new Text($this->faker->sentence),
            ]),
        ];
    }
}
