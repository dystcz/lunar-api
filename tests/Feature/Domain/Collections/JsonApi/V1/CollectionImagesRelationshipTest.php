<?php

use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list collection images through relationship', function () {
    /** @var TestCase $this */
    $collection = Collection::factory()
        ->withImages()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('media')
        ->get(serverUrl("/collections/{$collection->getRouteKey()}/images"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($collection->images)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count collection images', function () {
    /** @var TestCase $this */
    $collection = Collection::factory()
        ->withImages(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->get(serverUrl("/collections/{$collection->getRouteKey()}?with_count=images"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($collection);

    expect($response->json('data.relationships.images.meta.count'))->toBe(3);
})->group('products');
