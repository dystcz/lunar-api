<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Concerns;

use Dystcz\LunarApi\Domain\CollectionGroups\Factories\CollectionGroupFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): CollectionGroupFactory
    {
        return CollectionGroupFactory::new();
    }
}
