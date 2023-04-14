<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Illuminate\Support\Facades\App;

class LunarApi
{
    public static function createUserFromCartUsing(string $callback): void
    {
        App::singleton(CreatesUserFromCart::class, $callback);
    }

    public static function registerUserUsing(string $callback): void
    {
        App::singleton(RegistersUser::class, $callback);
    }
}
