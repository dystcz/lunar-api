<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\FilterCollection;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Products\JsonApi\Sorts\RecentlyViewedSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasManyThrough;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOneThrough;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use Lunar\Models\Product;

class ProductSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * The model the schema corresponds to.
     */
    public static string $model = Product::class;

    /**
     * Build an index query for this resource.
     */
    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Build a "relatable" query for this resource.
     */
    public function relatableQuery(?Request $request, Relation $query): Relation
    {
        return $query;
    }

    /**
     * The relationships that should always be eager loaded.
     */
    public function with(): array
    {
        return [
            ...parent::with(),

            'productType',
            'productType.mappedAttributes',
            'productType.mappedAttributes.attributeGroup',
        ];
    }

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            ...parent::includePaths(),

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
        ];
    }

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ...parent::fields(),

            ID::make(),

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
        ];
    }

    public function sortables(): iterable
    {
        return [
            ...parent::sortables(),

            RecentlyViewedSort::make('recently_viewed'),
        ];
    }

    /**
     * Get the resource filters.
     */
    public function filters(): array
    {
        /** @var FilterCollection $filterCollection */
        // $filterCollection = Config::get('lunar-api.domains.products.filters');

        return [
            ...parent::filters(),

            WhereIdIn::make($this),

            WhereHas::make($this, 'prices'),

            WhereHas::make($this, 'brand'),

            WhereHas::make($this, 'default_url', 'url')->singular(),

            // ...(new $filterCollection)->toArray(),
        ];
    }

    /**
     * Will the set of filters result in zero-to-one resource?
     *
     * While individual filters can be marked as singular, there may be instances
     * where the combination of filters should result in a singular response
     * (zero-to-one resource instead of zero-to-many). Developers can use this
     * hook to add complex logic for working out if a set of filters should
     * return a singular resource.
     */
    public function isSingular(array $filters): bool
    {
        // return isset($filters['userId'], $filters['clientId']);

        return false;
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(
                Config::get('lunar-api.domains.products.pagination', 12)
            );
    }

    /**
     * Determine if the resource is authorizable.
     */
    public function authorizable(): bool
    {
        return false; // TODO create policies
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'products';
    }
}
