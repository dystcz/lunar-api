<?php

namespace Dystcz\LunarApi\Domain\Users\Actions;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesNewUsers;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class RegisterUser implements RegistersUser
{
    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $data
     */
    public function __invoke(array $data): ?Authenticatable
    {
        $user = App::make(CreatesNewUsers::class)->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'] ?? Str::random(32),
        ]);

        Event::dispatch(new Registered($user));

        return $user;
    }
}
