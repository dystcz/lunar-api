<?php

namespace Dystcz\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;

class LunarApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lunar-api';
    }
}