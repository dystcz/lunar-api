<?php

namespace Dystcz\LunarApi\Domain\Addresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Factories\AddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Address as LunarAddress;

class Address extends LunarAddress
{
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
