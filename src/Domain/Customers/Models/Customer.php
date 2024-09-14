<?php

namespace Dystcz\LunarApi\Domain\Customers\Models;

use Dystcz\LunarApi\Domain\Customers\Concerns\InteractsWithLunarApi;
use Lunar\Models\Customer as LunarCustomer;

/**
 * @method HasMany attributes()
 */
class Customer extends LunarCustomer
{
    use InteractsWithLunarApi;
}
