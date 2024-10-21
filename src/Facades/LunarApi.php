<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string|null $name = null)
 * @method static \Dystcz\LunarApi\LunarApi authGuard(string $name) Set the auth guard
 * @method static string getAuthGuard() Get the auth guard
 * @method static \Dystcz\LunarApi\LunarApi createUserUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi createUserFromCartUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi registerUserUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi checkoutCartUsing(class-string $class)
 * @method static \Dystcz\LunarApi\LunarApi hashIds(bool $value) Set ID hashing
 * @method static bool usesHashids() Check if the API hashes resource IDs
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
