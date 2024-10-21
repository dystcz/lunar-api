<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Support\Testing\Fakes\NotificationFake;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth', 'users');

it('can register a user', function () {
    /** @var TestCase $this */
    $user = User::factory()->make();

    /** @var EventFake $eventFake */
    $eventFake = Event::fake();

    // /** @var NotificationFake $notificationFake */
    // $notificationFake = Notification::fake();

    $data = [
        'type' => 'users',
        'attributes' => [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'accept_terms' => true,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData($data)
        ->post('/api/v1/users');

    $id = $response
        ->assertCreatedWithServerId(
            Config::get('app.url').'/api/v1/users',
            Arr::only($data, ['email'])
        )
        ->id();

    $eventFake->assertDispatched(
        Registered::class,
        fn (Registered $event) => $event->user->email === $user->email,
    );

    $this->assertDatabaseHas((new User)->getTable(), [
        'id' => $id,
        'email' => $user->email,
    ]);
});
