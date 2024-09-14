<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    generateUrls();
});

it('can read default url through relationship', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('urls')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/default_url"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant->defaultUrl)
        ->assertDoesntHaveIncluded();
})->group('variants');
