<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\CartAddress;
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
