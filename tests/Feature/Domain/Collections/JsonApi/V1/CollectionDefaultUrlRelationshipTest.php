<?php

use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    generateUrls();
});

it('can read default url through relationship', function () {
    /** @var TestCase $this */
    $collection = Collection::factory()
        ->withImages()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('urls')
        ->get(serverUrl("/collections/{$collection->getRouteKey()}/default_url"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($collection->defaultUrl)
        ->assertDoesntHaveIncluded();
})->group('collections');
