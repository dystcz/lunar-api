<?php

namespace Dystcz\LunarApi\Domain\Customers\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\Customer;

class CustomerSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Customer::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'orders',
            'orders.lines',
            'orders.lines.purchasable',
            'addresses',
            'addresses.country',

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
            Str::make('title'),
            Str::make('first_name'),
            Str::make('last_name'),
            Str::make('company_name'),
            Str::make('account_ref'),
            Str::make('vat_no'),

            HasMany::make('orders')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),
            HasMany::make('addresses')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),
            BelongsToMany::make('users')
                ->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'customers';
    }
}
