<?php

use Dystcz\LunarApi\Base\Enums\PurchasableStatus;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lunar\FieldTypes\Text;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

it('can determine variant is always purchasable', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::ALWAYS->value,
                ]),
            'variants'
        )
        ->create();

    /** @var ProductVariant $variant */
    $variant = $product->variants->first();

    $this->assertTrue($variant->availability === Availability::ALWAYS);
})->group('products-variants', 'availability');

it('can determine variant is in stock', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::IN_STOCK->value,
                ]),
            'variants'
        )
        ->create();

    /** @var ProductVariant $variant */
    $variant = $product->variants->first();

    $this->assertFalse($variant->availability === Availability::IN_STOCK);

    $variant->update([
        'stock' => 1,
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::IN_STOCK);

})->group('products-variants', 'availability');

it('can determine variant is backorder', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'backorder' => 0,
                    'purchasable' => PurchasableStatus::BACKORDER->value,
                ]),
            'variants'
        )
        ->create();

    /** @var ProductVariant $variant */
    $variant = $product->variants->first();

    $this->assertFalse($variant->availability === Availability::BACKORDER);

    $variant->update([
        'backorder' => 1,
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::BACKORDER);

})->group('products-variants', 'availability');

it('can determine variant is preorder', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::IN_STOCK->value,
                ]),
            'variants'
        )
        ->create([
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
                'description' => new Text($this->faker->sentence),
                'eta' => new Text($this->faker->date),
            ]),
        ]);

    /** @var ProductVariant $variant */
    $variant = $product->variants->first();

    $this->assertFalse($variant->availability === Availability::PREORDER);

    $variant->update([
        'stock' => 1,
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::PREORDER);

    $variant->update([
        'stock' => 0,
        'purchasable' => PurchasableStatus::BACKORDER->value,
        'backorder' => 1,
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::PREORDER);

})->group('products-variants', 'availability');

it('can determine variant is out of stock', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::IN_STOCK->value,
                ]),
            'variants'
        )
        ->create();

    /** @var ProductVariant $variant */
    $variant = $product->variants->first();

    $this->assertTrue($variant->availability === Availability::OUT_OF_STOCK);

    $variant->update([
        'purchasable' => 'random',
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::OUT_OF_STOCK);

    $variant->update([
        'purchasable' => PurchasableStatus::BACKORDER->value,
        'backorder' => 0,
    ]);

    $variant->refresh();

    $this->assertTrue($variant->availability === Availability::OUT_OF_STOCK);

})->group('products-variants', 'availability');
