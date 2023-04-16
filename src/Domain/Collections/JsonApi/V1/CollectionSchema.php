<?php

namespace Dystcz\LunarApi\Domain\Collections\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Collection;

class CollectionSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Collection::class;

    /**
     * {@inheritDoc}
     */
    protected array $with = [
        'defaultUrl',
    ];

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'products.urls',
            'products.default_url',
            'products.images',
            'urls',
            'default_url',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('name'),

            HasOne::make('default_url', 'defaultUrl')
                ->retainFieldName(),

            HasOne::make('group'),

            HasMany::make('products'),

            HasMany::make('urls'),

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

            WhereHas::make($this, 'default_urls', 'url')->singular(),

            ...parent::filters(),
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
        return 'collections';
    }
}
