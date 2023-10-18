<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\HashIds\HashId;
use Lunar\Models\CartLine;

class CartLineSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = CartLine::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            Config::get('lunar-api.schemas.use_hashids', false)
                ? HashId::make()
                : ID::make(),

            Number::make('quantity'),
            ArrayHash::make('meta'),
            Number::make('purchasable_id'),
            Str::make('purchasable_type'),

            BelongsTo::make('cart'),
            MorphTo::make('purchasable', 'purchasable')
                ->types('products', 'variants'),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'cart-lines';
    }
}
