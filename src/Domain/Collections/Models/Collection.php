<?php

namespace Dystcz\LunarApi\Domain\Collections\Models;

use Dystcz\LunarApi\Base\Contracts\Translatable;
use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\Collections\Factories\CollectionFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\Collection as LunarCollection;

/**
 * @method HasMany attributes()
 */
class Collection extends LunarCollection implements Translatable
{
    use HashesRouteKey;
    use InteractsWithAttributes;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    }
}
