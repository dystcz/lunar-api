<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\SchemaType;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\Contracts\ProductOption;
use Lunar\Models\Contracts\ProductOptionValue;

class ProductOptionValueSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductOptionValue::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'product_option',

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
                    fn (ProductOptionValue $model, string $attribute) => $model->translate($attribute),
                ),

            BelongsTo::make('product_option', 'option')
                ->readOnly()
                ->type(SchemaType::get(ProductOption::class))
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
