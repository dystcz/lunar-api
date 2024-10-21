<?php

use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Testing\Fakes\EventFake;

use function Pest\Faker\fake;

uses(TestCase::class, RefreshDatabase::class)
    ->group('auth', 'users');

test('users can register without password', function () {
    /** @var TestCase $this */

    /** @var EventFake $eventFake */
    $event = Event::fake();

    $data = [
        'type' => 'auth',
        'attributes' => [
            'email' => $email = fake()->safeEmail(),
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('users')
        ->withData($data)
        ->post(serverUrl('/auth/-actions/register-without-password'));

    $id = $response
        ->assertCreatedWithServerId(
            serverUrl('/users', true),
            Arr::only($data, ['email'])
        )
        ->id();

    $event->assertDispatched(
        Registered::class,
        fn (Registered $event) => $event->user->email === $email,
    );

    $this->assertDatabaseHas((new User)->getTable(), [
        'id' => $id,
        'email' => $email,
    ]);
});
