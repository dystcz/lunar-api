<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Orders\Models\OrderLine;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Relations\MorphTo;
use LaravelJsonApi\Eloquent\Fields\Str;

class OrderLineSchema extends Schema
{
    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * {@inheritDoc}
     */
    public static string $model = OrderLine::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'order',
            'currency',
            'purchasable',
            'purchasable.product',
            'purchasable.product.thumbnail',

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('type'),
            Str::make('description'),
            Str::make('option'),
            Str::make('identifier'),
            Str::make('notes'),

            Number::make('unit_quantity'),
            Number::make('quantity'),

            Number::make('unit_price'),
            Number::make('sub_total'),
            Number::make('discount_total'),
            Number::make('tax_total'),
            Number::make('total'),

            ArrayHash::make('tax_breakdown'),

            BelongsTo::make('order'),
            BelongsTo::make('currency'),
            MorphTo::make('purchasable', 'purchasable')->types('products', 'variants'),

            ...parent::fields(),
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
        return 'order-lines';
    }
}
