<?php

namespace Dystcz\LunarApi\Domain\Customers\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Customers\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Customers\Contracts\Customer as CustomerContract;
use Lunar\Models\Contracts\Customer as LunarCustomerContract;
use Lunar\Models\Customer as LunarCustomer;

/**
 * @method HasMany attributes()
 */
#[ReplaceModel(LunarCustomerContract::class)]
class Customer extends LunarCustomer implements CustomerContract
{
    use InteractsWithLunarApi;
}
