<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOneThrough;
use LaravelJsonApi\Eloquent\Filters\WhereHas;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use Lunar\Models\ProductVariant;

class ProductVariantSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductVariant::class;

    /**
     * Build an index query for this resource.
     */
    public function indexQuery(?Request $request, Builder $query): Builder
    {
        return $query->whereHas(
            'product',
            fn ($query) => $query->where('status', '!=', 'draft'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function mergeIncludePathsFrom(): iterable
    {
        return [
            'product' => 'products',
            'other_variants' => 'variants',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'default_url',
            'images',
            'lowest_price',
            'prices',
            'thumbnail',
            'urls',
            'values',

            ...parent::includePaths(),
        ];

        // $includePaths = [
        //     'default_url',
        //     'images',
        //     'lowest_price',
        //     'prices',
        //     'thumbnail',
        //     'urls',
        //     'values',
        // ];

        // $otherVariantsIncludePaths = array_merge(
        //     ['other_variants'],
        //     array_map(
        //         fn (string $path) => "other_variants.{$path}",
        //         $includePaths,
        //     )
        // );

        return [
            ...$includePaths,
            // ...$otherVariantsIncludePaths,
            // ...$this->getIncludePathsFor('products', 'product'),
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

            BelongsTo::make('product'),

            HasMany::make('other_variants', 'otherVariants')
                ->type('variants')
                ->canCount()
                ->retainFieldName(),

            HasOne::make('lowest_price', 'lowestPrice')
                ->type('prices')
                ->retainFieldName(),

            HasMany::make('images', 'images')
                ->type('media')
                ->canCount(),

            HasMany::make('values', 'values')
                ->type('product-option-values')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasMany::make('prices')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            HasOne::make('default_url', 'defaultUrl')
                ->type('urls')
                ->retainFieldName(),

            HasMany::make('urls'),

            HasOneThrough::make('thumbnail')
                ->type('media'),

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
