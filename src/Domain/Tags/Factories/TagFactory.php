<?php

namespace Dystcz\LunarApi\Domain\Tags\Factories;

use Dystcz\LunarApi\Domain\Tags\Models\Tag;
use Lunar\Database\Factories\TagFactory as LunarTagFactory;

class TagFactory extends LunarTagFactory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
