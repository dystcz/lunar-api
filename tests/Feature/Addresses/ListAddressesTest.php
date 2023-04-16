<?php

namespace Dystcz\LunarApi\Tests\Feature\Addresses;

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()->create(['customer_id' => Auth::user()->customers->first()->id]);
});

it('can be listed', function () {
    $response = $this
        ->jsonApi()
        ->expects('addresses')
        ->get('/api/v1/addresses');

    $response->assertFetchedMany([$this->address]);
});
