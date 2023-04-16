<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;

class OrderSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Order::class;

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            'customer',
            'user',

            'currency',

            'addresses',
            'addresses.country',
            'shippingAddress',
            'shippingAddress.country',
            'billingAddress',
            'billingAddress.country',

            'lines',
            'lines.currency',
            'lines.purchasable',
            'lines.purchasable.prices',
            'lines.purchasable.images',
            'lines.purchasable.product',
            'lines.purchasable.product.thumbnail',

            'productLines',
            'productLines.currency',
            'productLines.purchasable',
            'productLines.purchasable.prices',
            'productLines.purchasable.images',
            'productLines.purchasable.product',
            'productLines.purchasable.product.thumbnail',

            'shippingLines',
            'shippingLines.currency',
            'shippingLines.purchasable',
            'shippingLines.purchasable.prices',
            'shippingLines.purchasable.images',
            'shippingLines.purchasable.product',
            'shippingLines.purchasable.product.thumbnail',

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
            BelongsTo::make('currency'),

            HasMany::make('addresses')->type('order-addresses'),
            HasOne::make('shippingAddress')->type('order-addresses'),
            HasOne::make('billingAddress')->type('order-addresses'),

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

            Where::make('user_id'),
            Where::make('reference')->singular(),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'orders';
    }
}
