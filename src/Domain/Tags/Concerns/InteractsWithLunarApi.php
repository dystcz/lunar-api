<?php

namespace Dystcz\LunarApi\Domain\Tags\Concerns;

use Dystcz\LunarApi\Domain\Tags\Factories\TagFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
