<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;

class CartSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Cart::class;

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
            'lines',
            'lines.purchasable',
            'lines.purchasable.prices',
            'lines.purchasable.product',
            'order',
            'order.productLines',
            'order.productLines.purchasable',
            'addresses',
            'shippingAddress',
            'shippingAddress.country',
            'billingAddress',
            'billingAddress.country',
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
            HasMany::make('lines')->type('cart-lines'),
            HasMany::make('addresses')->type('cart-addresses'),
            HasOne::make('shippingAddress')->type('cart-addresses'),
            HasOne::make('billingAddress')->type('cart-addresses'),
        ];
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'carts';
    }
}
