<?php

namespace Dystcz\LunarApi\Domain\Collections\Factories;

use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Lunar\Database\Factories\CollectionFactory as LunarCollectionFactory;

class CollectionFactory extends LunarCollectionFactory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
