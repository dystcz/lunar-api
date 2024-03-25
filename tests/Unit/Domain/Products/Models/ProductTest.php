<?php

use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Actions\IsInStock;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Lunar\Database\Factories\TagFactory;

uses(TestCase::class, RefreshDatabase::class);

test('product has prices relation', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect($product->prices)->toHaveCount(1);
});

test('product has lowest price relation', function () {
    /** @var Collection<Product> $products */
    $products = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new()->count(5))->count(5),
            'variants'
        )
        ->count(5)
        ->create();

    foreach ($products as $product) {
        expect($product->lowestPrice->price->value)
            ->toBe($product->prices()->orderBy('price')->first()->price->value);
    }
});

test('product has cheapest variant relation', function () {
    /** @var Collection<Product> $products */
    $products = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new()->count(5))->count(5),
            'variants'
        )
        ->count(5)
        ->create();

    foreach (Product::with('variants.lowestPrice')->get() as $product) {
        expect($product->cheapestVariant->id)
            ->toBe($product->variants->sortBy('lowestPrice.price')->first()->id);
    }
});

test('product has tags relation', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(TagFactory::new()->count(2))
        ->create();

    expect($product->tags)->toHaveCount(2);
});

test('product is in stock when any variant has stock', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'in_stock'])->has(PriceFactory::new())->count(5),
            'variants'
        )
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'in_stock', 'stock' => 44])->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect((new IsInStock())($product))->toBeTrue();
});

test('product is in stock when any variant has purchasable to always', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'in_stock'])->has(PriceFactory::new())->count(5),
            'variants'
        )
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'always'])->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect((new IsInStock())($product))->toBeTrue();
});

test('product is in stock when any variant can be purchased always', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'always'])->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect((new IsInStock())($product))->toBeTrue();
});

test('product is out of stock when any variant cannot be purchased', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->state(['purchasable' => 'in_stock'])->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect((new IsInStock())($product))->toBeFalse();
});
