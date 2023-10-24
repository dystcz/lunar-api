<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()
        ->create([
            'customer_id' => Auth::user()->customers->first()->getKey(),
        ]);
});

it('can show address', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('addresses')
        ->includePaths('country', 'customer')
        ->get('/api/v1/addresses/'.$this->address->getRouteKey());

    $response->assertFetchedOne($this->address);
});
