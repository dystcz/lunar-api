<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->customer = Auth::user()->customers->first();
});

it('can read a customer', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->get('/api/v1/customers/'.$this->customer->getRouteKey());

    $response
        ->assertFetchedOne($this->customer);
});

it('can read only logged in users customer', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->get('/api/v1/customers/'.$this->customer->getRouteKey());

    $response
        ->assertFetchedOne($this->customer);
})->todo();
