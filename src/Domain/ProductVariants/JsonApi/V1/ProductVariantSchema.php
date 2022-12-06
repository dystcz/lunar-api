<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use Lunar\Models\ProductVariant;

class ProductVariantSchema extends Schema
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
    public static string $model = ProductVariant::class;

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
            'defaultUrl',
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

            HasMany::make('media'),
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
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(12);
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'variants';
    }
}
