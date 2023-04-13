<?php

namespace Dystcz\LunarApi\Tests\Feature\Customers;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->customer = Auth::user()->customers->first();
});

it('can be read', function () {
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->get('/api/v1/customers/'.$this->customer->getRouteKey());

    $response->assertFetchedOne($this->customer);
});
