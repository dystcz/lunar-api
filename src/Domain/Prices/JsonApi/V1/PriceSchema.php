<?php

namespace Dystcz\LunarApi\Domain\Prices\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Prices\JsonApi\Filters\MaxPriceFilter;
use Dystcz\LunarApi\Domain\Prices\JsonApi\Filters\MinPriceFilter;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use Lunar\Models\Price;

class PriceSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Price::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Number::make('price')
                ->serializeUsing(static fn ($value) => $value->decimal),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            MinPriceFilter::make('min_price', 'price'),

            MaxPriceFilter::make('max_price', 'price'),

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
        return 'prices';
    }
}
