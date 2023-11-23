<?php

namespace Dystcz\LunarApi\Domain\Customers\Models;

use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\Customers\Factories\CustomerFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Lunar\Models\Customer as LunarCustomer;

/**
 * @method HasMany attributes()
 */
class Customer extends LunarCustomer
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
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }
}
