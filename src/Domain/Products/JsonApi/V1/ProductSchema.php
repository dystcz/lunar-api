<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Products\JsonApi\Sorts\RecentlyViewedSort;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasManyThrough;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOneThrough;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Product;

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
        'attributes',
        'attributes.attributeGroup',
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

            HasMany::make('associations')->canCount(),

            BelongsTo::make('brand'),

            HasOne::make('cheapest_variant', 'cheapestVariant')
                ->type('variants')
                ->withUriFieldName('cheapest_variant'),

            HasMany::make('collections')
                ->canCount(),

            HasOne::make('default_url', 'defaultUrl')
                ->retainFieldName(),

            HasMany::make('images', 'images')
                ->type('media')
                ->canCount(),

            HasOneThrough::make('lowest_price', 'lowestPrice')
                ->type('prices')
                ->retainFieldName(),

            HasManyThrough::make('prices'),

            HasMany::make('tags')
                ->canCount(),

            HasOne::make('thumbnail', 'thumbnail')
                ->type('media'),

            HasMany::make('urls'),

            HasMany::make('variants')
                ->canCount(),

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
