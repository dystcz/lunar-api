<?php

namespace Dystcz\LunarApi\Domain\Tags\Models;

use Dystcz\LunarApi\Domain\Tags\Contracts\Tag as TagContract;
use Dystcz\LunarApi\Domain\Tags\Factories\TagFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Tag as LunarTag;

class Tag extends LunarTag implements TagContract
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
