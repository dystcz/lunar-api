<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

it('works', function () {
    $response = $this
        ->jsonApi()
        ->expects('countries')
        ->get('/api/v1/countries');

    $response->assertFetchedMany(Country::all());
});
