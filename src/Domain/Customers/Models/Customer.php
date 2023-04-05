<?php

namespace Dystcz\LunarApi\Domain\Customers\Models;

use Dystcz\LunarApi\Domain\Customers\Factories\CustomerFactory;
use Lunar\Models\Customer as LunarCustomer;

class Customer extends LunarCustomer
{
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
