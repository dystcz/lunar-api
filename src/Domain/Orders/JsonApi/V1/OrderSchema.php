<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Order;

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
            'addresses',
            'addresses.country',

            'currency',

            'customer',

            'addresses',
            'addresses.country',
            'shippingAddress',
            'shippingAddress.country',
            'billingAddress',
            'billingAddress.country',

            // Order Lines
            'lines',
            'lines.currency',

            // Product lines
            'productLines',
            'productLines.currency',
            'productLines.purchasable',
            'productLines.purchasable.prices',
            'productLines.purchasable.images',
            'productLines.purchasable.product',
            'productLines.purchasable.product.thumbnail',

            // Physical product lines
            'physicalLines',
            'physicalLines.currency',
            'physicalLines.purchasable',
            'physicalLines.purchasable.prices',
            'physicalLines.purchasable.images',
            'physicalLines.purchasable.product',
            'physicalLines.purchasable.product.thumbnail',

            // Digital product lines
            'digitalLines',
            'digitalLines.currency',
            'digitalLines.purchasable',
            'digitalLines.purchasable.prices',
            'digitalLines.purchasable.images',
            'digitalLines.purchasable.product',
            'digitalLines.purchasable.product.thumbnail',

            // Shipping lines
            'shippingLines',
            'shippingLines.currency',

            'user',

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
            Map::make('prices', [
                Number::make('sub_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('total', 'total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('tax_total', 'tax_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('discount_total', 'discount_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('shipping_total', 'shipping_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                ArrayHash::make('tax_breakdown'),
                ArrayHash::make('discount_breakdown'),
                Number::make('exchange_rate'),
            ]),
            DateTime::make('placed_at'),
            DateTime::make('created_at'),
            DateTime::make('updated_at'),
            // ArrayHash::make('meta'),

            HasMany::make('lines')->type('order-lines'),
            HasMany::make('productLines')->type('order-lines'),
            HasMany::make('digitalLines')->type('order-lines'),
            HasMany::make('physicalLines')->type('order-lines'),
            HasMany::make('shippingLines')->type('order-lines'),

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
