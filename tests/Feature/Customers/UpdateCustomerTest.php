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

    $this->data = [
        'id' => (string) $this->customer->getRouteKey(),
        'type' => 'customers',
        'attributes' => [
            'first_name' => 'Jane',
            'last_name' => $this->customer->last_name,
        ],
    ];
});

it('can be updated', function () {
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->withData($this->data)
        ->patch('/api/v1/customers/'.$this->customer->getRouteKey());

    $response->assertFetchedOne($this->customer);

    $this->assertDatabaseHas($this->customer->getTable(), [
        'id' => $this->customer->getRouteKey(),
        'first_name' => 'Jane',
    ]);
});
