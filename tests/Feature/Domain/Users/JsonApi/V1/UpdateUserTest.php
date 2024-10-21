<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class)
    ->group('users');

test('users can update their profile', function () {
    /** @var TestCase $this */
    $this->actingAs($user = User::factory()->create([
        'name' => 'Pikachu',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '1234567890',
    ]));

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData([
            'id' => (string) $user->getRouteKey(),
            'type' => 'users',
            'attributes' => [
                'name' => 'Raichu',
                'first_name' => 'Pepa',
                'last_name' => 'Lopata',
                'phone' => '0987654321',
            ],
        ])
        ->patch(serverUrl("users/{$user->getRouteKey()}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($user);

    $this->assertDatabaseHas('users', [
        'id' => $user->getKey(),
        'name' => 'Raichu',
        'first_name' => 'Pepa',
        'last_name' => 'Lopata',
        'phone' => '0987654321',
    ]);

});
