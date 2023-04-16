<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;

class CartSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Cart::class;

    /**
     * {@inheritDoc}
     */
    protected bool $selfLink = false;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'lines',
            'lines.purchasable',
            'lines.purchasable.prices',
            'lines.purchasable.product',
            'lines.purchasable.product.thumbnail',
            'order',
            'order.productLines',
            'order.productLines.purchasable',
            'order.productLines.purchasable.thumbnail',
            'addresses',
            'addresses.country',
            'shippingAddress',
            'shippingAddress.country',
            'billingAddress',
            'billingAddress.country',

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

            Map::make('prices', [
                Number::make('sub_total', 'subTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('sub_total_discounted', 'subTotalDiscounted')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('total', 'total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('shipping_total', 'shippingTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('tax_total', 'taxTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('discount_total', 'discountTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                ArrayHash::make('tax_breakdown', 'taxBreakdown'),
                ArrayHash::make('discount_breakdown', 'discountBreakdown'),
            ]),

            Str::make('coupon_code'),

            // Custom fields (not in the database)
            Boolean::make('create_user')->hidden(),

            BelongsTo::make('order')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('lines')->type('cart-lines')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('addresses')->type('cart-addresses')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOne::make('shippingAddress')->type('cart-addresses')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOne::make('billingAddress')->type('cart-addresses')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'carts';
    }
}
