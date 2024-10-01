<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Contracts\Product;
use Lunar\Models\Contracts\ProductAssociation;

class ProductAssociationSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ProductAssociation::class;

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
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('type'),

            HasOne::make('target')
                ->type(ModelType::get(Product::class)),

            HasOne::make('parent')
                ->type(ModelType::get(Product::class)),

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

            Where::make('type'),

            ...parent::filters(),
        ];
    }
}
