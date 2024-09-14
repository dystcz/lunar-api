<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Models;

use Dystcz\LunarApi\Domain\CollectionGroups\Contracts\CollectionGroup as CollectionGroupContract;
use Dystcz\LunarApi\Domain\CollectionGroups\Factories\CollectionGroupFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\CollectionGroup as LunarCollectionGroup;

class CollectionGroup extends LunarCollectionGroup implements CollectionGroupContract
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
