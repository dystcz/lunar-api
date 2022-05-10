<?php

namespace Dystcz\GetcandyApi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dystcz\GetcandyApi\Skeleton\SkeletonClass
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
