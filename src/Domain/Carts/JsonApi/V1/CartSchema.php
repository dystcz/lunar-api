<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Discounts\Data\DiscountBreakdown;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\SchemaType;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Resources\Relation;
use Lunar\Base\ValueObjects\Cart\DiscountBreakdown as LunarDiscountBreakdown;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\Contracts\CartAddress;
use Lunar\Models\Contracts\CartLine;
use Lunar\Models\Contracts\Order;

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
            'cart_lines',
            'cart_lines.purchasable',
            'cart_lines.purchasable.default_url',
            'cart_lines.purchasable.images',
            'cart_lines.purchasable.prices',
            'cart_lines.purchasable.product',
            'cart_lines.purchasable.product.collections',
            'cart_lines.purchasable.product.default_url',
            'cart_lines.purchasable.product.images',
            'cart_lines.purchasable.product.product_type',
            'cart_lines.purchasable.product.thumbnail',
            'cart_lines.purchasable.thumbnail',
            'cart_lines.purchasable.values',

            'order',
            'order.product_lines',
            'order.product_lines.purchasable',
            'order.product_lines.purchasable.thumbnail',
            'order.product_lines.purchasable.default_url',
            'order.product_lines.purchasable.product',
            'order.product_lines.purchasable.product.thumbnail',
            'order.product_lines.purchasable.product.default_url',

            'cart_addresses',
            'cart_addresses.country',

            'shipping_address',
            'shipping_address.country',

            'billing_address',
            'billing_address.country',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Map::make('prices', [
                Number::make('sub_total', 'subTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('sub_total_discounted', 'subTotalDiscounted')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('total', 'total')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('shipping_sub_total', 'shippingSubTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('shipping_total', 'shippingTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('payment_sub_total', 'paymentSubTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('payment_total', 'paymentTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('tax_total', 'taxTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                Number::make('discount_total', 'discountTotal')
                    ->serializeUsing(static fn ($value) => $value?->decimal),

                ArrayHash::make('tax_breakdown', 'taxBreakdown')
                    ->serializeUsing(static fn ($value) => $value?->amounts),

                ArrayHash::make('discount_breakdown', 'discountBreakdown')
                    ->serializeUsing(static fn ($value) => $value?->map(
                        fn (LunarDiscountBreakdown $discountBreakdown) => (new DiscountBreakdown($discountBreakdown))->toArray(),
                    )),
            ]),

            Str::make('coupon_code'),

            Str::make('payment_option'),

            // Custom fields (not in the database)
            Boolean::make('create_user')
                ->hidden(),

            ArrayHash::make('meta'),

            Boolean::make('agree')
                ->hidden(),

            HasOne::make('order', 'draftOrder')
                ->type(SchemaType::get(Order::class))
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasMany::make('cart_lines', 'lines')
                ->type(SchemaType::get(CartLine::class))
                ->retainFieldName(),

            HasMany::make('cart_addresses', 'addresses')
                ->type(SchemaType::get(CartAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasOne::make('shipping_address', 'shippingAddress')
                ->type(SchemaType::get(CartAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasOne::make('billing_address', 'billingAddress')
                ->type(SchemaType::get(CartAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            ...parent::fields(),
        ];
    }
}
