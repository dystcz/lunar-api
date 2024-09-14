<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
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

            BelongsTo::make('option', 'option')
                ->readOnly()
                ->type(ModelType::get(ProductOption::class)),

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
