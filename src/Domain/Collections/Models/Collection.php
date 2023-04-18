<?php

namespace Dystcz\LunarApi\Domain\Collections\Models;

use Dystcz\LunarApi\Domain\Collections\Factories\CollectionFactory;
use Lunar\Models\Collection as LunarCollection;

class Collection extends LunarCollection
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    }
}
