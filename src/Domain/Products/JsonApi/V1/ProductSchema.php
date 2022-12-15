<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\Products\JsonApi\Sorting\RecentlyViewedSort;
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
use LaravelJsonApi\Eloquent\Schema;
use Lunar\Models\Product;

class ProductSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     *
     * @var array|null
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Product::class;

    /**
     * Build an index query for this resource.
     *
     * @param Request|null $request
     * @param Builder $query
     * @return Builder
     */
    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query;
    }

    /**
     * Build a "relatable" query for this resource.
     *
     * @param Request|null $request
     * @param Relation $query
     * @return Relation
     */
    public function relatableQuery(?Request $request, Relation $query): Relation
    {
        return $query;
    }

    /**
     * The relationships that should always be eager loaded.
     *
     * @return array
     */
    public function with(): array
    {
        return [
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
            'associations',
            'associations.target.variants.prices',
            'associations.target.thumbnail',
            'brand',
            'brand.thumbnail',
            'default_url',
            'images',
            'thumbnail',
            'urls',
            'prices',
            'lowestPrice',
            'cheapestVariant',
            'cheapestVariant.images',
            'cheapestVariant.prices',
            'variants',
            'variants.images',
            'variants.prices',
        ];
    }

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),

            BelongsTo::make('brand'),
            HasOne::make('default_url', 'defaultUrl'),
            HasOne::make('thumbnail'),
            HasOne::make('cheapestVariant')->type('variants'),
            HasMany::make('urls'),
            HasMany::make('images')->canCount(),
            HasMany::make('variants')->canCount(),
            HasMany::make('associations')->canCount(),
            HasOneThrough::make('lowestPrice')->type('prices'),
            HasManyThrough::make('prices'),
        ];
    }

    public function sortables(): iterable
    {
        return [
            RecentlyViewedSort::make('recently_viewed'),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            WhereHas::make($this, 'default_url', 'url')->singular(),
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
     *
     * @param array $filters
     * @return bool
     */
    public function isSingular(array $filters): bool
    {
        // return isset($filters['userId'], $filters['clientId']);

        return false;
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(
                Config::get('lunar-api.domains.products.pagination', 12)
            );
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'products';
    }
}
