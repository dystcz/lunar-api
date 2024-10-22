<?php

namespace Dystcz\LunarApi\Domain\Collections\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Collections\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Collections\Contracts\Collection as CollectionContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection as LaravelCollection;
use Lunar\Models\Collection as LunarCollection;
use Lunar\Models\Contracts\Collection as LunarCollectionContract;

/**
 * @method HasMany attributes()
 * @method MorphMany images()
 *
 * @property LaravelCollection $images
 */
#[ReplaceModel(LunarCollectionContract::class)]
class Collection extends LunarCollection implements CollectionContract
{
    use InteractsWithLunarApi;
}
