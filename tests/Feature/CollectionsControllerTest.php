<?php

use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\CollectionFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(PriceFactory::new(), 'basePrices'),
                    'variants'
                )
        )
        ->count(3)
        ->create();

    $response = $this->get(
        Config::get('lunar-api.route_prefix') .
        '/collections' .
        '?include=products.variants.basePrices,products.defaultUrl' .
        '&fields[lunar_collections]=id,attribute_data'
    );

    $response->assertStatus(200);

    expect($response->json('data'))->toHaveCount(3);
})->skip();

it('can read collection detail', function () {
    $collection = CollectionFactory::new()->create();

    $response = $this->get(Config::get('lunar-api.route_prefix') . '/collections/' . $collection->defaultUrl->slug);

    $response->assertStatus(200);

    expect($response->json('data.id'))->toBe((string) $collection->id);
})->skip();

it('can read products in a collection', function () {
    $collection = CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(
                            PriceFactory::new()
                        )
                        ->count(1),
                    'variants'
                )
                ->count(1)
        )
        ->create();

    $response = $this->get(
        Config::get('lunar-api.route_prefix') . '/collections/' . $collection->defaultUrl->slug . '?include=products'
    );

    $response->assertStatus(200);
})->skip();
