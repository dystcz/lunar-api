<?php

namespace Dystcz\LunarApi\Tests\Feature\Countries;

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

it('works', function () {
    $country = Country::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects('countries')
        ->get('/api/v1/countries');

    $response->assertFetchedMany([$country]);
});
