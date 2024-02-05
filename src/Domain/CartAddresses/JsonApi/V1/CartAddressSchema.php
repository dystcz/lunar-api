<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\CartAddress;

class CartAddressSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = CartAddress::class;

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            'cart',
            'country',

            // ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            $this->idField(),

            Str::make('title'),
            Str::make('first_name'),
            Str::make('last_name'),
            Str::make('company_name'),
            Str::make('company_in', 'meta')
                ->serializeUsing(
                    static fn (?ArrayObject $value) => $value?->collect()->get('company_in') ?? null,
                ),
            Str::make('company_tin', 'meta')
                ->serializeUsing(
                    static fn (?ArrayObject $value) => $value?->collect()->get('company_tin') ?? null,
                ),
            Str::make('line_one'),
            Str::make('line_two'),
            Str::make('line_three'),
            Str::make('city'),
            Str::make('state'),
            Str::make('postcode'),
            Str::make('delivery_instructions'),
            Str::make('contact_email'),
            Str::make('contact_phone'),
            Str::make('shipping_option'),
            Str::make('address_type', 'type'),

            ArrayHash::make('meta'),

            BelongsTo::make('country'),
            BelongsTo::make('cart'),

            // ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'cart-addresses';
    }
}
