<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

uses(TestCase::class, RefreshDatabase::class);

it('can list other product variants through relationship', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(3)
        ->create();

    $variant = $variants->first();
    $otherVariants = $variants->where('id', '!=', $variant->id);

    $response = $this
        ->jsonApi()
        ->expects(ModelType::get(ProductVariantContract::class))
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/other_variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($otherVariants)
        ->assertDoesntHaveIncluded();
})->group('variants');
