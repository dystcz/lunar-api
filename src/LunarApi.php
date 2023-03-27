<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;

class LunarApi
{
    public static function createUserFromCartUsing(string $callback): void
    {
        app()->singleton(CreatesUserFromCart::class, $callback);
    }

    public static function registerUserUsing(string $callback): void
    {
        app()->singleton(RegistersUser::class, $callback);
    }
}
