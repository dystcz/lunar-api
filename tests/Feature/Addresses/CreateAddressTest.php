<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()->has(Customer::factory())->create();

    $this->address = Address::factory()->make([
        'postcode' => $this->faker->postcode,
    ]);

    $this->data = [
        'type' => 'addresses',
        'attributes' => [
            'first_name' => $this->address->first_name,
            'last_name' => $this->address->last_name,
            'company_name' => $this->address->company_name,
            'city' => $this->address->city,
            'line_one' => $this->address->line_one,
            'line_two' => $this->address->line_two,
            'line_three' => $this->address->line_three,
            'postcode' => $this->address->postcode,
            'company_in' => '123456789',
            'company_tin' => 'CZ123456789',
        ],
        'relationships' => [
            'customer' => [
                'data' => [
                    'type' => 'customers',
                    'id' => (string) $this->user->customers->first()->getRouteKey(),
                ],
            ],
            'country' => [
                'data' => [
                    'type' => 'countries',
                    'id' => (string) Country::factory()->create()->getRouteKey(),
                ],
            ],
        ],
    ];
});

test('address can be created', function () {
    /** @var TestCase $this */
    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->withData($this->data)
        ->includePaths('customer', 'country')
        ->post('/api/v1/addresses');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/addresses', $this->data)
        ->id();

    $this->assertDatabaseHas($this->address->getTable(), [
        'id' => $id,
        'first_name' => $this->address->first_name,
        'last_name' => $this->address->last_name,
        'company_name' => $this->address->company_name,
        'city' => $this->address->city,
        'line_one' => $this->address->line_one,
        'line_two' => $this->address->line_two,
        'line_three' => $this->address->line_three,
        'postcode' => $this->address->postcode,
        'meta' => json_encode([
            'company_in' => '123456789',
            'company_tin' => 'CZ123456789',
        ]),
    ]);
});
