<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Resources\Relation;
use Lunar\Models\Contracts\Order;
use Lunar\Models\Contracts\OrderAddress;
use Lunar\Models\Contracts\OrderLine;
use Lunar\Models\Contracts\Transaction;

class OrderSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Order::class;

    protected $defaultSort = '-created_at';

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            // Addresses
            'order-addresses',
            'order-addresses.country',

            // Shipping address
            'shipping-address',
            'shipping-address.country',

            // Billing address
            'billing-address',
            'billing-address.country',

            // Currency
            'currency',

            // Customer
            'customer',

            // Digital product lines
            'digital-lines',
            'digital-lines.currency',
            'digital-lines.purchasable',
            'digital-lines.purchasable.prices',
            'digital-lines.purchasable.images',
            'digital-lines.purchasable.thumbnail',
            'digital-lines.purchasable.product',
            'digital-lines.purchasable.product.thumbnail',

            // Order Lines
            'order-lines',
            'order-lines.currency',

            // Physical product lines
            'physical-lines',
            'physical-lines.currency',
            'physical-lines.purchasable',
            'physical-lines.purchasable.prices',
            'physical-lines.purchasable.images',
            'physical-lines.purchasable.thumbnail',
            'physical-lines.purchasable.product',
            'physical-lines.purchasable.product.thumbnail',

            // Product lines
            'product-lines',
            'product-lines.currency',
            'product-lines.purchasable',
            'product-lines.purchasable.prices',
            'product-lines.purchasable.images',
            'product-lines.purchasable.thumbnail',
            'product-lines.purchasable.product',
            'product-lines.purchasable.product.thumbnail',

            // Shipping lines
            'shipping-lines',
            'shipping-lines.currency',

            // Payment lines
            'payment-lines',
            'payment-lines.currency',

            // Transactions
            'transactions',
            'transactions.currency',

            // User
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
            $this->idField(),

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
                Number::make('payment_total', 'payment_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                ArrayHash::make('tax_breakdown', 'taxBreakdown')
                    ->serializeUsing(
                        static fn ($value) => $value?->amounts,
                    ),
                ArrayHash::make('shipping_breakdown')
                    ->serializeUsing(
                        static fn ($value) => $value?->items,
                    ),
                ArrayHash::make('payment_breakdown')
                    ->serializeUsing(
                        static fn ($value) => $value?->items,
                    ),
                ArrayHash::make('discount_breakdown', 'discountBreakdown')
                    ->serializeUsing(
                        static fn ($value) => $value,
                    ),
                Number::make('exchange_rate'),
            ]),
            DateTime::make('placed_at')
                ->sortable(),

            DateTime::make('created_at')
                ->sortable(),

            DateTime::make('updated_at')
                ->sortable(),

            Str::make('payment_method')
                ->hidden(),

            Str::make('amount')
                ->hidden(),

            ArrayHash::make('meta'),

            HasMany::make('order-lines', 'lines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            HasMany::make('product-lines', 'productLines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            HasMany::make('digital-lines', 'digitalLines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            HasMany::make('physical-lines', 'physicalLines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            HasMany::make('shipping-lines', 'shippingLines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            HasMany::make('payment-lines', 'paymentLines')
                ->retainFieldName()
                ->type(ModelType::get(OrderLine::class)),

            BelongsTo::make('customer')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            BelongsTo::make('user')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            BelongsTo::make('currency')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasMany::make('order-addresses', 'addresses')
                ->type(ModelType::get(OrderAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasOne::make('shipping-address', 'shippingAddress')
                ->type(ModelType::get(OrderAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasOne::make('billing-address', 'billingAddress')
                ->type(ModelType::get(OrderAddress::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasOne::make('latest-transaction', 'latestTransaction')
                ->type(ModelType::get(Transaction::class))
                ->retainFieldName()
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            HasMany::make('transactions')
                ->type(ModelType::get(Transaction::class))
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

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
}
