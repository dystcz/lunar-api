<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
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
            ID::make(),

            AttributeData::make('attribute_data')
                ->groupAttributes(),

            BelongsTo::make('product'),

            HasOne::make('lowest_price', 'lowestPrice')->type('prices'),

            HasMany::make('images')
                ->canCount()
                ->type('media'),

            HasMany::make('prices'),

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
