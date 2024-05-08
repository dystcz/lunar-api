<?php

use Dystcz\LunarApi\Domain\Tags\Models\Tag;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read tag detail', function () {
    /** @var TestCase $this */
    $tag = Tag::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects('tags')
        ->get(serverUrl("/tags/{$tag->getRouteKey()}"));

    $response
        ->assertFetchedOne($tag)
        ->assertDoesntHaveIncluded();
})->group('tags');

it('returns error response when tag does not exist', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('tags')
        ->get(serverUrl('/tags/1'));

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
})->group('tags');
