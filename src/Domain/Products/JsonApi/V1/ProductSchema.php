<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Products\JsonApi\Sorts\RecentlyViewedSort;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasManyThrough;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOneThrough;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;

class ProductSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Product::class;

    /**
     * {@inheritDoc}
     */
    protected array $with = [
        'productType',
        'productType.mappedAttributes',
        'productType.mappedAttributes.attributeGroup',
    ];

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'default_url',
            'images',
            'lowest_price',
            'prices',
            'thumbnail',
            'urls',

            'associations',
            'associations.target.thumbnail',
            'associations.target.variants.prices',

            'brand',
            'brand.default_url',
            'brand.thumbnail',

            'cheapest_variant',
            'cheapest_variant.images',
            'cheapest_variant.prices',

            'collections',
            'collections.default_url',
            'collections.group',
            'collections.urls',

            'variants',
            'variants.images',
            'variants.prices',
            // 'variants.thumbnail',

            'tags',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            ID::make(),

            AttributeData::make('attribute_data')
                ->groupAttributes(),

            HasMany::make('associations')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation,
                ),

            BelongsTo::make('brand'),

            HasOne::make('cheapest_variant', 'cheapestVariant')
                ->type('variants')
                ->withUriFieldName('cheapest_variant'),

            HasMany::make('collections')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks()
                ),

            HasOne::make('default_url', 'defaultUrl')
                ->retainFieldName(),

            HasMany::make('images', 'images')
                ->type('media')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOneThrough::make('lowest_price', 'lowestPrice')
                ->type('prices')
                ->retainFieldName()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasManyThrough::make('prices')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('tags')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOne::make('thumbnail', 'thumbnail')
                ->type('media')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('urls')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('variants')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            // HasMany::make('attributes')
            //     ->hidden()
            //     ->serializeUsing(
            //         static fn ($relation) => $relation->withoutLinks(),
            //     ),

            // BelongsTo::make('product_type', 'productType')
            //     ->hidden()
            //     ->retainFieldName()
            //     ->serializeUsing(
            //         static fn ($relation) => $relation->withoutLinks(),
            //     ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return [
            RecentlyViewedSort::make('recently_viewed'),

            ...parent::sortables(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        /** @var FilterCollection $filterCollection */
        // $filterCollection = Config::get('lunar-api.domains.products.filters');

        return [
            WhereIdIn::make($this),

            WhereHas::make($this, 'prices'),

            WhereHas::make($this, 'brand'),

            WhereHas::make($this, 'default_url', 'url')->singular(),

            // ...(new $filterCollection)->toArray(),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'products';
    }
}
