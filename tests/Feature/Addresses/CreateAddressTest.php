<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Address;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()->make();

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
        ],
        'relationships' => [
            'customer' => [
                'data' => [
                    'type' => 'customers',
                    'id' => (string) Auth::user()->customers->first()->getRouteKey(),
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

it('can be created', function () {
    $response = $this
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
    ]);
});
