<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

class LunarApiConfig extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lunar-api-config';
    }
}
