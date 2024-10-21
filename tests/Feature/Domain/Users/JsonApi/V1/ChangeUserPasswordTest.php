<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth', 'users');

test('users can change their password', function () {
    /** @var TestCase $this */
    $this->actingAs($user = User::factory()->create([
        'password' => $oldPassword = Hash::make('password123xxx'),
    ]));

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData([
            'id' => (string) $user->getRouteKey(),
            'type' => 'users',
            'attributes' => [
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
                'old_password' => 'password123xxx',
            ],
        ])
        ->patch(serverUrl("/users/{$user->getRouteKey()}/-actions/change-password"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($user);

    $user = $user->fresh();

    $this->assertNotSame($user->password, $oldPassword);
});
