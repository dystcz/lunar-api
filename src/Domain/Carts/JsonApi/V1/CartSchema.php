<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\ID;
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

            BelongsTo::make('order'),
            HasMany::make('lines')->type('cart-lines'),
            HasMany::make('addresses')->type('cart-addresses'),
            HasOne::make('shippingAddress')->type('cart-addresses'),
            HasOne::make('billingAddress')->type('cart-addresses'),

            Str::make('coupon_code'),

            // Custom fields (not in the database)
            Boolean::make('create_user')->hidden(),

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
