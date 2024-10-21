<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth');

it('can log in a user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this
        ->post(serverUrl('/auth/-actions/login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

    $response->assertSuccessful();
});
