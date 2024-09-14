<?php

namespace Dystcz\LunarApi\Domain\Tags\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIn;
use Lunar\Models\Contracts\Tag;

class TagSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Tag::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            // 'taggables',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('value'),

            // MorphTo::make('taggables', 'taggables')
            //     ->type('products'),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this)->delimiter(','),

            Where::make('value'),

            WhereIn::make('values', 'value')->delimiter(','),

            ...parent::filters(),
        ];
    }
}
