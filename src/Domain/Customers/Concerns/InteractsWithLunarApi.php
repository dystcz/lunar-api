<?php

namespace Dystcz\LunarApi\Domain\Customers\Concerns;

use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\Customers\Factories\CustomerFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait InteractsWithLunarApi
{
    use HashesRouteKey;
    use InteractsWithAttributes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /**
     * Get name attribute.
     */
    protected function name(): Attribute
    {
        return $this->fullName();
    }

    /**
     * Get full name attribute.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->first_name && ! $this->last_name) {
                    return null;
                }

                return implode(' ', array_filter([$this->first_name, $this->last_name]));
            }
        );
    }
}
