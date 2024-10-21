<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth', 'users');

test('users can set a new password with a correct token', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    DB::table('password_resets')->insert([
        'email' => $user->email,
        'token' => $token = Password::createToken($user),
    ]);

    $data = [
        'type' => 'auth',
        'attributes' => [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData($data)
        ->post(serverUrl('/auth/-actions/reset-password'));

    $response->assertFetchedNull()
        ->assertExactMeta([
            'success' => true,
        ]);
});
