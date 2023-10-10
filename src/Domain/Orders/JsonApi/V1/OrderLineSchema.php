<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\OrderLine;

class OrderLineSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * {@inheritDoc}
     */
    public static string $model = OrderLine::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'currency',

            'order',

            'purchasable',
            'purchasable.images',
            'purchasable.prices',
            'purchasable.product',
            'purchasable.product.thumbnail',

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

            Str::make('type'),
            Str::make('description'),
            Str::make('option'),
            Str::make('identifier'),
            Str::make('notes'),

            Map::make('prices', [
                Number::make('unit_price')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('unit_quantity'),
                Number::make('quantity'),
                Number::make('sub_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('sub_total_discounted')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('total', 'total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('tax_total', 'taxTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('discount_total', 'discount_total')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                ArrayHash::make('tax_breakdown'),
                ArrayHash::make('shipping_breakdown'),
                ArrayHash::make('discount_breakdown'),
            ]),

            BelongsTo::make('order')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            BelongsTo::make('currency')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            MorphTo::make('purchasable', 'purchasable')
                ->types('products', 'variants', 'shipping-options')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'order-lines';
    }
}
