<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\HashIds\HashId;
use Lunar\Models\ProductAssociation;

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
            // Association
            'target',
            'target.thumbnail',
            'target.variants',
            'target.variants.prices',

            // Inverse association
            'parent',
            'parent.thumbnail',
            'parent.variants',
            'parent.variants.prices',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            Config::get('lunar-api.schemas.use_hashids', false)
                ? HashId::make()
                : ID::make(),

            Str::make('type'),

            HasOne::make('target')
                ->type('products'),

            HasOne::make('parent')
                ->type('products'),

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

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'associations';
    }
}
