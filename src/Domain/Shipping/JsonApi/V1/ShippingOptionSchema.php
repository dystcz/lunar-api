<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use Dystcz\LunarApi\Domain\Shipping\Entities\ShippingOption;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\NonEloquent\Fields\Attribute;
use LaravelJsonApi\NonEloquent\Fields\ID;

class ShippingOptionSchema extends Schema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = ShippingOption::class;

    /**
     * @inheritDoc
     */
    public function fields(): iterable
    {
        return [
            ID::make(),
            Attribute::make('name'),
            Attribute::make('description'),
            Attribute::make('identifier'),
            Attribute::make('price'),
        ];
    }

    public function authorizable(): bool
    {
        return false;
    }

    public function repository(): ShippingOptionRepository
    {
        return ShippingOptionRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
    }
}