<?php

namespace Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\Contracts\ProductType;

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
            $this->idField(),
            Str::make('name'),

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
    public function filters(): array
    {
        return [
            ...parent::filters(),
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
