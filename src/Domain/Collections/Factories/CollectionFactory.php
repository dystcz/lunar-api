<?php

namespace Dystcz\LunarApi\Domain\Collections\Factories;

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\CollectionFactory as LunarCollectionFactory;

class CollectionFactory extends LunarCollectionFactory
{
    /**
     * Create a model with images.
     *
     * @param  array<string,mixed>  $state
     */
    public function withImages(int $count = 1, array $state = []): self
    {
        $prefix = Config::get('lunar.database.table_prefix');

        return $this
            ->has(
                MediaFactory::new()
                    ->state(fn ($data, $model) => array_merge([]), $state)
                    ->count($count),
                'images',
            );
    }
}
