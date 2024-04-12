<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
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
            'images',
            // 'thumbnail',

            'values',
            'prices',
            'product',
            'product.thumbnail',

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

            Number::make('sku'),

            Number::make('stock'),

            Number::make('backorder'),

            Str::make('purchasable'),

            AttributeData::make('attribute_data')
                ->groupAttributes(),

            Map::make('purchasability', [
                ArrayHash::make('purchase_status')
                    ->extractUsing(
                        static fn (ProductVariant $model) => $model->purchaseStatus->toArray()
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

            // HasOne::make('thumbnail'),

            ...parent::fields(),
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
