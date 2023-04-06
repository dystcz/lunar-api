<?php

namespace Dystcz\LunarApi\Domain\Users\Actions;

use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterUser implements RegistersUser
{
    public function __invoke(array $data): ?Authenticatable
    {
        Event::dispatch(
            new Registered(
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'] ?? Hash::make(Str::random()),
                ])
            )
        );

        // TODO: Login the user after registation
        // App::make(StatefulGuard::class)->login($user);

        return $user;
    }
}
