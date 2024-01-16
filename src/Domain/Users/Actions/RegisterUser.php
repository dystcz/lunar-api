<?php

namespace Dystcz\LunarApi\Domain\Users\Actions;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesNewUsers;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Contracts\UserData as UserDataContract;
use Dystcz\LunarApi\Domain\Users\Data\UserData;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class RegisterUser implements RegistersUser
{
    public function __construct(
        protected CreatesNewUsers $createUser,
    ) {
    }

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $data
     */
    public function register(UserDataContract $data): Authenticatable
    {
        $user = $this->createUser($data);

        Event::dispatch(new Registered($user));

        return $user;
    }

    /**
     * Create a new user instance.
     *
     * @param  array<string, string>  $data
     */
    protected function createUser(UserDataContract $data): Authenticatable
    {
        $data = new UserData(
            name: $data->name(),
            email: $data->email(),
            password: $data->password() ?? Str::random(32),
        );

        return $this->createUser->create($data);
    }
}
