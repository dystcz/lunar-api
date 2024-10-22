<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\CollectionGroups\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\CollectionGroups\Contracts\CollectionGroup as CollectionGroupContract;
use Lunar\Models\CollectionGroup as LunarCollectionGroup;
use Lunar\Models\Contracts\CollectionGroup as LunarCollectionGroupContract;

#[ReplaceModel(LunarCollectionGroupContract::class)]
class CollectionGroup extends LunarCollectionGroup implements CollectionGroupContract
{
    use InteractsWithLunarApi;
}
