<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class OrderSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * The model the schema corresponds to.
     */
    public static string $model = Order::class;

    /**
     * The relationships that should always be eager loaded.
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

            'customer',
            'user',

            'lines',
            'lines.currency',
            'lines.purchasable',
            'lines.purchasable.prices',
            'lines.purchasable.images',
            'lines.purchasable.product',

            'productLines',
            'productLines.currency',
            'productLines.purchasable',
            'productLines.purchasable.prices',
            'productLines.purchasable.images',
            'productLines.purchasable.product',

            'shippingLines',
            'shippingLines.currency',
            'shippingLines.purchasable',
            'shippingLines.purchasable.prices',
            'shippingLines.purchasable.images',
            'shippingLines.purchasable.product',
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
            Boolean::make('new_customer'),
            Str::make('status'),
            Str::make('reference'),
            Str::make('notes'),
            Str::make('currency_code'),
            Str::make('compare_currency_code'),
            Number::make('sub_total'),
            Number::make('discount_total'),
            Number::make('shipping_total'),
            Number::make('tax_total'),
            Number::make('total'),
            Number::make('exchange_rate'),
            DateTime::make('placed_at'),
            DateTime::make('created_at'),
            DateTime::make('updated_at'),
            // ArrayHash::make('meta'),

            HasMany::make('lines')->type('order-lines'),
            HasMany::make('shippingLines')->type('order-lines'),
            HasMany::make('productLines')->type('order-lines'),
            BelongsTo::make('customer'),
            BelongsTo::make('user'),
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
     */
    public function filters(): array
    {
        return [
            ...parent::filters(),

            WhereIdIn::make($this),
            Where::make('user_id'),
            Where::make('reference')->singular(),
        ];
    }

    /**
     * Get the resource paginator.
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(
                Config::get('lunar-api.domains.orders.pagination', 12)
            );
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'orders';
    }
}
