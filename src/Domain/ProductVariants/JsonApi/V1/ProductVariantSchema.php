<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Resources\Relation;
use Lunar\Models\ProductVariant;

class ProductVariantSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductVariant::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'default_url',
            'images',
            'prices',
            'product',
            'product.thumbnail',
            'urls',
            'values',

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

            AttributeData::make('attribute_data')
                ->groupAttributes(),

            Map::make('availability', [
                ArrayHash::make('stock')
                    ->extractUsing(
                        static fn (ProductVariant $model) => [
                            'quantity_string' => $model->approximateInStockQuantity,
                        ],
                    ),
                ArrayHash::make('status')
                    ->extractUsing(
                        static fn (ProductVariant $model) => $model->availability->toArray()
                    ),
            ]),

            BelongsTo::make('product')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOne::make('lowest_price', 'lowestPrice')
                ->type('prices')
                ->retainFieldName()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('images', 'images')
                ->type('media')
                ->canCount()
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('values', 'values')
                ->type('product-option-values'),
            // ->canCount()
            // ->serializeUsing(
            // static fn ($relation) => $relation->withoutLinks(),
            // ),

            HasMany::make('prices')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            // HasOne::make('default_url', 'defaultUrl')
            //     ->type('urls')
            //     ->retainFieldName(),
            //
            // HasMany::make('urls')
            //     ->serializeUsing(static fn (Relation $relation) => $relation->withoutLinks()),

            // HasOne::make('thumbnail'),

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

            WhereIdNotIn::make($this),

            WhereHas::make($this, 'urls', 'url')
                ->singular(),

            WhereHas::make($this, 'urls', 'urls'),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'variants';
    }
}
