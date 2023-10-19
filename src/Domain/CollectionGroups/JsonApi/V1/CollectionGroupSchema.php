<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\CollectionGroup;

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

            Str::make('value'),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'collection-groups';
    }
}
