<?php

namespace Dystcz\LunarApi\Domain\Addresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Concerns\InteractsWithLunarApi;
use Lunar\Models\Address as LunarAddress;

class Address extends LunarAddress
{
    use InteractsWithLunarApi;
}
