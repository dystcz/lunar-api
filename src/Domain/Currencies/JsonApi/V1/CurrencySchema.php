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
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Currency::class;

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

            Str::make('code'),
            Str::make('name'),

            Number::make('exchange_rate'),
            Number::make('decimal_places'),

            Boolean::make('enabled'),
            Boolean::make('default'),

            HasMany::make('prices')
        ];
    }

    public function authorizable(): bool
    {
        return false;
    }

    /**
     * Get the JSON:API resource type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'currencies';
    }
}
