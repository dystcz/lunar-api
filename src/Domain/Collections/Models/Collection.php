<?php

namespace Dystcz\LunarApi\Domain\Collections\Models;

use Dystcz\LunarApi\Domain\Collections\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Collections\Contracts\Collection as CollectionContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection as LaravelCollection;
use Lunar\Models\Collection as LunarCollection;

/**
 * @method HasMany attributes()
 * @method MorphMany images()
 *
 * @property LaravelCollection $images
 */
class Collection extends LunarCollection implements CollectionContract
{
    use InteractsWithLunarApi;
}
