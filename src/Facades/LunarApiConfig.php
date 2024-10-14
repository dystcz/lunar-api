<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getSchemas()
 * @method static \Illuminate\Support\Collection getRoutes()
 * @method static \Illuminate\Support\Collection getModels()
 * @method static \Illuminate\Support\Collection getPolicies()
 *
 * @see \Dystcz\LunarApi\LunarApiConfig
 */
class LunarApiConfig extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lunar-api-config';
    }
}
