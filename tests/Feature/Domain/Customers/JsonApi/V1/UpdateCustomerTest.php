<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();

    $this->customer = $this->user->customers->first();

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
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('customers')
        ->withData($this->data)
        ->patch(serverUrl("/customers/{$this->customer->getRouteKey()}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($this->customer);

    $this->assertDatabaseHas($this->customer->getTable(), [
        'id' => $this->customer->getKey(),
        'first_name' => 'Jane',
        'last_name' => $this->customer->last_name,
        'vat_no' => 'CZ123456789',
        'account_ref' => '123456789',
    ]);
})->group('customers');

it('can only customer when logged in', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('customers')
        ->withData($this->data)
        ->patch(serverUrl("/customers/{$this->customer->getRouteKey()}"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('customers');
