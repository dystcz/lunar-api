<?php

namespace Dystcz\LunarApi\Domain\Addresses\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Addresses\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Addresses\Contracts\Address as AddressContract;
use Lunar\Models\Address as LunarAddress;
use Lunar\Models\Contracts\Address as LunarAddressContract;

#[ReplaceModel(LunarAddressContract::class)]
class Address extends LunarAddress implements AddressContract
{
    use InteractsWithLunarApi;
}
