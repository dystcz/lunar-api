<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Tag;

uses(TestCase::class, RefreshDatabase::class);

it('can list product tags through relationship', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(Tag::factory()->count(3), 'tags')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('tags')
        ->get(serverUrl("/products/{$product->getRouteKey()}/tags"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->tags)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product tags', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(Tag::factory()->count(4), 'tags')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with_count=tags"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.tags.meta.count'))->toBe(4);
})->group('products', 'counts');
