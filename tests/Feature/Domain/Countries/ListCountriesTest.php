<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

it('can list countries', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('countries')
        ->get('/api/v1/countries');

    $response->assertFetchedMany(Country::all());
})->group('countries');
