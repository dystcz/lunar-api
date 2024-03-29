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

    $this->data = [
        'id' => (string) $this->customer->getRouteKey(),
        'type' => 'customers',
        'attributes' => [
            'first_name' => 'Jane',
            'last_name' => $this->customer->last_name,
            'vat_no' => 'CZ123456789',
            'account_ref' => '123456789',
        ],
    ];
});

it('can update a customer by logged in user', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->withData($this->data)
        ->patch('/api/v1/customers/'.$this->customer->getRouteKey());

    $response->assertFetchedOne($this->customer);

    $this->assertDatabaseHas($this->customer->getTable(), [
        'id' => $this->customer->getKey(),
        'first_name' => 'Jane',
        'last_name' => $this->customer->last_name,
        'vat_no' => 'CZ123456789',
        'account_ref' => '123456789',
    ]);
});

it('only updates logged in users customer', function () {
    /** @var TestCase $this */

    //
})->todo();
