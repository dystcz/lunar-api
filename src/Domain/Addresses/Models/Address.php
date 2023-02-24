<?php

namespace Dystcz\LunarApi\Domain\Addresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Factories\AddressFactory;
use Lunar\Models\Address as LunarAddress;

class Address extends LunarAddress
{
    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
