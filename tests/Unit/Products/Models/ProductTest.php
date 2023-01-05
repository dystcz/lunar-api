<?php

use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('has prices relation', function () {
    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new()),
            'variants'
        )
        ->create();

    expect($product->prices)->toHaveCount(1);
});

it('has lowest price relation', function () {
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

it('has cheapest variant relation', function () {
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
