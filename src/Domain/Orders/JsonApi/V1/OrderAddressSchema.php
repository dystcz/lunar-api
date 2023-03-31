<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo;
use LaravelJsonApi\Eloquent\Fields\Str;
use Lunar\Models\OrderAddress;

class OrderAddressSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     */
    public static string $model = OrderAddress::class;

    /**
     * The relationships that should always be eager loaded.
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
            'order',
            'country',
        ];
    }

    /**
     * Get the resource fields.
     */
    public function fields(): array
    {
        return [
            ...parent::fields(),

            ID::make(),

            Str::make('title'),
            Str::make('first_name'),
            Str::make('last_name'),
            Str::make('company_name'),
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

            BelongsTo::make('order'),
            BelongsTo::make('country'),
        ];
    }

    /**
     * Get the JSON:API resource type.
     */
    public static function type(): string
    {
        return 'order-addresses';
    }
}
