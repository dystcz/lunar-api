<?php

namespace Dystcz\LunarApi\Domain\Currencies\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\Currency;

class CurrencySchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Currency::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ID::make(),

            Str::make('code'),
            Str::make('name'),

            Number::make('exchange_rate'),
            Number::make('decimal_places'),

            Boolean::make('enabled'),
            Boolean::make('default'),

            HasMany::make('prices'),
        ];
    }

    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'currencies';
    }
}
