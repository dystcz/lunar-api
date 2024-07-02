<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getPackageRoot()
 * @method static void createUserUsing(string $class)
 * @method static void createUserFromCartUsing(string $class)
 * @method static void registerUserUsing(string $class)
 * @method static void checkoutCartUsing(string $class)
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
