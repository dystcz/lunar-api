<?php

namespace Dystcz\LunarApi\Domain\Customers\JsonApi\V1;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Str;

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

            HasMany::make('orders'),
            HasMany::make('addresses'),
            BelongsToMany::make('users'),

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
