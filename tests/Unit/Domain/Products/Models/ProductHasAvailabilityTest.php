<?php

use Dystcz\LunarApi\Base\Enums\PurchasableStatus;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lunar\FieldTypes\Text;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

it('can determine product is in stock', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::IN_STOCK->value,
                ])
                ->count(2),
            'variants'
        )
        ->create();

    $this->assertFalse($product->availability === Availability::IN_STOCK);

    $product->variants[1]->update([
        'stock' => 1,
    ]);

    $product = $product->fresh(['variants']);

    $this->assertTrue($product->availability === Availability::IN_STOCK);

})->group('product_variants', 'availability');

it('can determine product is backorder', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'backorder' => 0,
                    'purchasable' => PurchasableStatus::BACKORDER->value,
                ])
                ->count(3),
            'variants'
        )
        ->create();

    $this->assertFalse($product->availability === Availability::BACKORDER);

    $product->variants[1]->update([
        'backorder' => 1,
    ]);

    $product = $product->fresh(['variants']);

    $this->assertTrue($product->availability === Availability::BACKORDER);

})->group('product_variants', 'availability');

it('can determine product is preorder', function () {
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

    $this->assertTrue($product->availability === Availability::PREORDER);
})->group('product_variants', 'availability');

it('can determine product is out of stock', function () {
    /** @var TestCase $this */
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(PriceFactory::new())
                ->state([
                    'stock' => 0,
                    'purchasable' => PurchasableStatus::IN_STOCK->value,
                ])
                ->count(3),
            'variants'
        )
        ->create();

    $this->assertTrue($product->availability === Availability::OUT_OF_STOCK);
})->group('product_variants', 'availability');
