<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Factories;

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Support\Str;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRateAmount;

class ProductVariantFactory extends \Lunar\Database\Factories\ProductVariantFactory
{
    protected $model = ProductVariant::class;

    public function withPrice(int $price = null, int $comparePrice = null): static
    {
        return $this->has(
            Price::factory()->state([
                'price' => $price ?? $this->faker->numberBetween(100, 1000),
                'compare_price' => $comparePrice ?? $this->faker->numberBetween(100),
                'currency_id' => Currency::getDefault(),
            ]),
        );
    }

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'tax_class_id' => TaxClass::factory()->hasTaxRateAmounts(
                TaxRateAmount::factory()
            ),
            'sku' => Str::random(12),
            'unit_quantity' => 1,
            'gtin' => $this->faker->unique()->isbn13,
            'mpn' => $this->faker->unique()->isbn13,
            'ean' => $this->faker->unique()->ean13,
            'shippable' => true,
        ];
    }
}
