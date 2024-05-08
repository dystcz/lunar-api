<?php

namespace Dystcz\LunarApi\Domain\Products\Factories;

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Illuminate\Support\Facades\Config;
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
            'brand_id' => Brand::factory(),
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
            ProductVariantFactory::new()->count($count)->withPrice(),
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
