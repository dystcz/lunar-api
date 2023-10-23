<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\LunarApi;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()
        ->create([
            'customer_id' => Auth::user()->customers->first()->id,
            'postcode' => $this->faker->postcode,
        ]);

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
            'company_in' => '123456789',
            'company_tin' => '987654321',
        ],
    ];
});

it('can be updated', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('addresses')
        ->withData($this->data)
        ->patch('/api/v1/addresses/'.$this->address->getRouteKey());

    $response->assertFetchedOne($this->address);

    $id = $this->address->getRouteKey();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($this->address, $id);
    }

    $this->assertDatabaseHas($this->address->getTable(), [
        'id' => $id,
        'first_name' => 'John',
        'meta' => json_encode(
            array_merge(
                $this->address->meta?->toArray() ?? [],
                [
                    'company_in' => '123456789',
                    'company_tin' => '987654321',
                ],
            ),
        ),
    ]);
});
