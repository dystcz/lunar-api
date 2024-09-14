<?php

namespace Dystcz\LunarApi\Domain\Products\Factories;

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\ProductFactory as LunarProductFactory;
use Lunar\FieldTypes\Text;
use Lunar\Models\Brand;
use Lunar\Models\ProductType;
use Lunar\Models\ProductVariant;

class ProductFactory extends LunarProductFactory
{
    public function definition(): array
    {
        return [
            'product_type_id' => ProductType::modelClass()::factory(),
            'status' => 'published',
            'brand_id' => Brand::modelClass()::factory(),
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
                'description' => new Text($this->faker->sentence),
            ]),
        ];
    }

    /**
     * Create a model with (product variant) price.
     *
     * @param  array<string,mixed>  $state
     */
    public function withPrice(): self
    {
        return $this->withPrices(1);
    }

    /**
     * Create a model with (product variant) prices.
     *
     * @param  array<string,mixed>  $state
     */
    public function withPrices(int $count = 1): self
    {
        return $this->has(
            ProductVariant::modelClass()::factory()->count($count)->withPrice(),
            'variants',
        );
    }

    /**
     * Create a model with thumbnail.
     *
     * @param  array<string,mixed>  $state
     */
    public function withThumbnail(array $state = []): self
    {
        $prefix = Config::get('lunar.database.table_prefix');

        return $this->has(
            MediaFactory::new()->state(fn ($data, $model) => array_merge([
                'custom_properties' => [
                    'primary' => true,
                ],
            ], $state)),
            'thumbnail',
        );
    }

    /**
     * Create a model with images.
     *
     * @param  array<string,mixed>  $state
     */
    public function withImages(int $count = 1, array $state = []): self
    {
        $prefix = Config::get('lunar.database.table_prefix');

        return $this
            ->has(
                MediaFactory::new()
                    ->state(fn ($data, $model) => array_merge([]), $state)
                    ->count($count),
                'images',
            );
    }
}
