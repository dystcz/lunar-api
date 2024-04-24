<?php

namespace Dystcz\LunarApi\Domain\Collections\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Sorts\InDefaultOrder;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Filters\Has;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereNull;
use LaravelJsonApi\Eloquent\Resources\Relation;
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
    protected $defaultSort = 'ordered';

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'default_url',
            'images',
            'thumbnail',
            'urls',

            'group',

            'products',
            'products.default_url',
            'products.images',
            'products.lowest_price',
            'products.prices',
            'products.thumbnail',
            'products.urls',

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

            AttributeData::make('attribute_data')
                ->groupAttributes(),

            Number::make('parent_id', 'parent_id')
                ->hidden(),

            HasOne::make('default_url', 'defaultUrl')
                ->type('urls')
                ->retainFieldName(),

            HasMany::make('images', 'images')
                ->type('media')
                ->canCount(),

            BelongsTo::make('group', 'group')
                ->type('collection-groups')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasMany::make('products')
                ->canCount(),

            HasOne::make('thumbnail', 'thumbnail')
                ->type('media'),

            HasMany::make('urls')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return [
            ...parent::sortables(),

            InDefaultOrder::make('ordered'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            WhereHas::make($this, 'urls', 'url')
                ->singular(),

            WhereHas::make($this, 'urls', 'urls'),

            WhereHas::make($this, 'group', 'group'),

            WhereNull::make('root', 'parent_id'),

            Has::make($this, 'products', 'has_products'),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'collections';
    }
}
