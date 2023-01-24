<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;

class CartLineSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = CartLine::class;

    /**
     * The relationships that should always be eager loaded.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            ...parent::with(),
        ];
    }

    /**
     * Get the include paths supported by this resource.
     *
     * @return string[]|iterable
     */
    public function includePaths(): iterable
    {
        return [
            ...parent::includePaths(),
        ];
    }

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ...parent::fields(),

            ID::make(),
            Number::make('quantity'),
            ArrayList::make('meta'),
            Number::make('purchasable_id'),
            Str::make('purchasable_type'),

            BelongsTo::make('cart'),
            MorphTo::make('purchasable', 'purchasable')->types('products', 'variants'),
        ];
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'cart-lines';
    }
}
