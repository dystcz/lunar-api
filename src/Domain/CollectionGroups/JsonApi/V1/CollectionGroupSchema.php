<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Contracts\CollectionGroup;

class CollectionGroupSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = CollectionGroup::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('name'),

            Str::make('handle'),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            Where::make('name', 'name'),

            Where::make('handle', 'handle'),

            ...parent::filters(),
        ];
    }
}
