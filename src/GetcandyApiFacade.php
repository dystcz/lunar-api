<?php

namespace Dystcz\GetcandyApi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dystcz\GetcandyApi\GetcandyApi
 */
class GetcandyApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'getcandy-api';
    }
}
