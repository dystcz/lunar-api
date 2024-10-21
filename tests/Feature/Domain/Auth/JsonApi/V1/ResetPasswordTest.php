<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\NotificationFake;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth', 'users');

it('can send password reset links to users', function () {
    /** @var TestCase $this */

    /** @var NotificationFake $notificationFake */
    $notificationFake = Notification::fake();

    $user = User::factory()->create();

    $data = [
        'type' => 'auth',
        'attributes' => [
            'email' => $user->email,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData($data)
        ->post(serverUrl('/auth/-actions/forgot-password'));

    $notificationFake->assertSentTo(
        [$user],
        Config::get('lunar-api.domains.auth.notifications.reset_password')
    );

    $response->assertFetchedNull()
        ->assertExactMeta([
            'message' => __('auth.password_reset.confirmation'),
            'success' => true,
        ]);
});
