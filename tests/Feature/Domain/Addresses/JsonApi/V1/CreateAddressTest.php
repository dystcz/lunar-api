<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Countries\Models\Country;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();
});

it('can store addresses', function () {
    /** @var TestCase $this */
    $fakeModel = Address::factory()->make([
        'postcode' => $this->faker->postcode,
    ]);

    $data = [
        'type' => 'addresses',
        'attributes' => [
            'first_name' => $fakeModel->first_name,
            'last_name' => $fakeModel->last_name,
            'company_name' => $fakeModel->company_name,
            'city' => $fakeModel->city,
            'line_one' => $fakeModel->line_one,
            'line_two' => $fakeModel->line_two,
            'line_three' => $fakeModel->line_three,
            'postcode' => $fakeModel->postcode,
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

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->withData($data)
        ->includePaths(
            'customer',
            'country',
        )
        ->post(serverUrl('/addresses'));

    $id = $response
        ->assertCreatedWithServerId(serverUrl('/addresses', true), $data)
        ->id();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($fakeModel, $id);
    }

    $this->assertDatabaseHas($fakeModel->getTable(), [
        'id' => $id,
        'first_name' => $fakeModel->first_name,
        'last_name' => $fakeModel->last_name,
        'company_name' => $fakeModel->company_name,
        'city' => $fakeModel->city,
        'line_one' => $fakeModel->line_one,
        'line_two' => $fakeModel->line_two,
        'line_three' => $fakeModel->line_three,
        'postcode' => $fakeModel->postcode,
        'meta' => json_encode([
            'company_in' => '123456789',
            'company_tin' => 'CZ123456789',
        ]),
    ]);
})->group('addresses');
