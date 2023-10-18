<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use Dystcz\LunarApi\Domain\Shipping\Entities\ShippingOption;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\HashIds\HashId;
use LaravelJsonApi\NonEloquent\Fields\Attribute;
use LaravelJsonApi\NonEloquent\Fields\ID;

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
            Config::get('lunar-api.schemas.use_hashids', false)
                ? HashId::make()
                : ID::make(),

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
