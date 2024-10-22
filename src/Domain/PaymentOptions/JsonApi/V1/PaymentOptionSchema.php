<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Core\Schema\TypeResolver;
use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;
use LaravelJsonApi\Core\Schema\Schema;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\NonEloquent\Fields\Attribute;
use LaravelJsonApi\NonEloquent\Fields\ID;

class PaymentOptionSchema extends Schema
{
    /**
     * Whether resources of this type have a self link.
     */
    protected bool $selfLink = false;

    /**
     * {@inheritDoc}
     */
    public static string $model = PaymentOption::class;

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
            Attribute::make('currency'),
            Attribute::make('default'),
            Attribute::make('driver'),

            ArrayHash::make('meta'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function repository(): PaymentOptionRepository
    {
        return PaymentOptionRepository::make()
            ->withServer($this->server)
            ->withSchema($this);
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
    public static function type(): string
    {
        $resolver = new TypeResolver;

        return $resolver(static::class);
    }

    /**
     * {@inheritDoc}
     */
    public function uriType(): string
    {
        if ($this->uriType) {
            return $this->uriType;
        }

        return $this->uriType = $this->type();
    }
}
