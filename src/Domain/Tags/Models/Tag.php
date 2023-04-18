<?php

namespace Dystcz\LunarApi\Domain\Tags\Models;

use Dystcz\LunarApi\Domain\Tags\Factories\TagFactory;
use Lunar\Models\Tag as LunarTag;

class Tag extends LunarTag
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
