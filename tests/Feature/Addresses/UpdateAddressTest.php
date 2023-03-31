<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Address;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()->create(['customer_id' => Auth::user()->customers->first()->id]);

    $this->data = [
        'id' => (string) $this->address->getRouteKey(),
        'type' => 'addresses',
        'attributes' => [
            'first_name' => 'John',
            'last_name' => $this->address->last_name,
            'company_name' => $this->address->company_name,
            'city' => $this->address->city,
            'line_one' => $this->address->line_one,
            'line_two' => $this->address->line_two,
            'line_three' => $this->address->line_three,
            'postcode' => $this->address->postcode,
            'meta' => [
                'vat_no' => '123456789',
                'account_no' => '987654321'
            ],
        ],
    ];
});

it('can be updated', function () {
    $response = $this
        ->jsonApi()
        ->expects('addresses')
        ->withData($this->data)
        ->patch('/api/v1/addresses/'.$this->address->getRouteKey());

    $response->assertFetchedOne($this->address);

    $this->assertDatabaseHas($this->address->getTable(), [
        'id' => $this->address->getRouteKey(),
        'first_name' => 'John',
        'meta' => json_encode([
            'vat_no' => '123456789',
            'account_no' => '987654321'
        ]),
    ]);
});
