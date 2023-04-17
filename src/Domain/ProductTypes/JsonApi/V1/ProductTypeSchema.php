<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use Lunar\Models\ProductType;

class ProductTypeSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductType::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            // 'mapped_attributes',
            // 'mapped_attributes.attribute_group',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            ID::make(),

            // HasMany::make('mapped_attributes', 'mappedAttributes')
            //     ->type('attributes')
            //     ->serializeUsing(
            //         static fn ($relation) => $relation->withoutLinks()
            //     ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'product-types';
    }
}
