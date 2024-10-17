<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\SchemaType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\Contracts\ProductOption;
use Lunar\Models\Contracts\ProductOptionValue;

class ProductOptionSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductOption::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
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

            Str::make('name')
                ->readOnly()
                ->extractUsing(
                    fn (ProductOption $model, string $attribute) => $model->translate($attribute),
                ),

            Str::make('label')
                ->readOnly()
                ->extractUsing(
                    fn (ProductOption $model, string $attribute) => $model->translate($attribute),
                ),

            Str::make('handle')
                ->readOnly(),

            HasMany::make('product_option_values', 'values')
                ->type(SchemaType::get(ProductOptionValue::class))
                ->canCount()
                ->countAs('product_option_values_count')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks()
                ),

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
}
