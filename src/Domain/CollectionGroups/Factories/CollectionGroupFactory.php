<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Factories;

use Dystcz\LunarApi\Domain\CollectionGroups\Models\CollectionGroup;
use Lunar\Database\Factories\CollectionGroupFactory as LunarCollectionGroupFactory;

class CollectionGroupFactory extends LunarCollectionGroupFactory
{
    protected $model = CollectionGroup::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
