<?php

namespace Dystcz\LunarApi\Domain\CartLines\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\SchemaType;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Resources\Relation;
use Lunar\Models\Contracts\CartLine;
use Lunar\Models\Contracts\Product;
use Lunar\Models\Contracts\ProductVariant;

class CartLineSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = CartLine::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'purchasable',
            'purchasable.images',
            'purchasable.prices',
            'purchasable.thumbnail',
            'purchasable.product',
            'purchasable.product.images',
            'purchasable.product.thumbnail',
            'purchasable.product_option_values',
            'purchasable.product_option_values.product_option',

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

            Number::make('purchasable_id'),
            Str::make('purchasable_type'),

            Map::make('prices', [
                Number::make('unit_price', 'unitPrice')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('quantity', 'quantity'),
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
                Number::make('tax_amount', 'taxAmount')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
                Number::make('discount_total', 'discounTotal')
                    ->serializeUsing(
                        static fn ($value) => $value?->decimal,
                    ),
            ]),

            ArrayHash::make('meta'),

            BelongsTo::make('cart')
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            MorphTo::make('purchasable', 'purchasable')
                ->types(
                    SchemaType::get(Product::class),
                    SchemaType::get(ProductVariant::class),
                )
                ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            ...parent::fields(),
        ];
    }
}
