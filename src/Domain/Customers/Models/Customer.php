<?php

namespace Dystcz\LunarApi\Domain\Customers\Models;

use Dystcz\LunarApi\Domain\Customers\Factories\CustomerFactory;
use Lunar\Models\Customer as LunarCustomer;

class Customer extends LunarCustomer
{
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
