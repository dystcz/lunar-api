<?php

namespace Dystcz\LunarApi\Domain\Addresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Addresses\Contracts\Address as AddressContract;
use Lunar\Models\Address as LunarAddress;

class Address extends LunarAddress implements AddressContract
{
    use InteractsWithLunarApi;
}
