<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string|null $name = null)
 * @method static \Dystcz\LunarApi\LunarApi authGuard(string $name)
 * @method static string getAuthGuard()
 * @method static \Dystcz\LunarApi\LunarApi createUserUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi createUserFromCartUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi registerUserUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi checkoutCartUsing(class-string $class)
 * @method static bool usesHashids()
 *
 * @see \Dystcz\LunarApi\LunarApi
 */
class LunarApi extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lunar-api';
    }
}
