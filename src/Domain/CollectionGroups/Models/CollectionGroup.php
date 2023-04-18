<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Models;

use Dystcz\LunarApi\Domain\CollectionGroups\Factories\CollectionGroupFactory;
use Lunar\Models\CollectionGroup as LunarCollectionGroup;

class CollectionGroup extends LunarCollectionGroup
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): CollectionGroupFactory
    {
        return CollectionGroupFactory::new();
    }
}
