<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read thumbnail through relationship', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->withThumbnail()
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('media')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/thumbnail"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant->thumbnail)
        ->assertDoesntHaveIncluded();
})->group('product-variants');
