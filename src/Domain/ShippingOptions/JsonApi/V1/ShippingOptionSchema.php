<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\ShippingOptions\Entities\ShippingOption;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\NonEloquent\Fields\Attribute;

class ShippingOptionSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = ShippingOption::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [
            ID::make(),

            Attribute::make('name'),
            Attribute::make('description'),
            Attribute::make('identifier'),
            Attribute::make('price'),
            ArrayHash::make('meta'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function repository(): ShippingOptionRepository
    {
        return ShippingOptionRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'shipping-options';
    }
}
