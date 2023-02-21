<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Orders\Models\OrderLine;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class OrderLineSchema extends Schema
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
    public static string $model = OrderLine::class;

    /**
     * The relationships that should always be eager loaded.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            ...parent::with(),
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
            'order',
            'purchasable',
            'purchasable.product',
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
            ...parent::fields(),

            ID::make(),
            BelongsTo::make('order'),
            MorphTo::make('purchasable', 'purchasable')->types('products', 'variants'),
        ];
    }

    public function sortables(): iterable
    {
        return [
            ...parent::sortables(),
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
            ...parent::filters(),

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
            ->withDefaultPerPage(
                Config::get('lunar-api.domains.order_lines.pagination', 12)
            );
    }

    /**
     * Determine if the resource is authorizable.
     *
     * @return bool
     */
    public function authorizable(): bool
    {
        return false; // TODO create policies
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'order-lines';
    }
}
