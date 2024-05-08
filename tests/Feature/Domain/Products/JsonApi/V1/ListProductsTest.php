<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list bare products', function () {
    /** @var TestCase $this */
    $products = Product::factory()
        ->has(
            ProductVariant::factory()->has(Price::factory())->count(2),
            'variants'
        )
        ->count(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl('/products'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($products)
        ->assertDoesntHaveIncluded();
})->group('products');

it('cannot list unpublished products', function () {
    /** @var TestCase $this */
    $products = Product::factory()
        ->has(
            ProductVariant::factory()->has(Price::factory())->count(2),
            'variants'
        )
        ->count(3)
        ->create(['status' => 'draft']);

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl('/products'));

    $response
        ->assertSuccessful()
        ->assertFetchedNone()
        ->assertDoesntHaveIncluded();
})->group('products', 'policies');
