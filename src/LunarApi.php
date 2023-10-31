<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LunarApi
{
    /**
     * Create user from cart using callback.
     */
    public static function createUserFromCartUsing(string $callback): void
    {
        App::singleton(CreatesUserFromCart::class, $callback);
    }

    /**
     * Register user using callback.
     */
    public static function registerUserUsing(string $callback): void
    {
        App::singleton(RegistersUser::class, $callback);
    }

    /**
     * Check if hashids are used.
     */
    public static function usesHashids(): bool
    {
        return Config::get('lunar-api.general.use_hashids', false);
    }
}
