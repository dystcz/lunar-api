<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Traits\HasCompanyIdentifiersInMeta;
use Dystcz\LunarApi\Domain\OrderAddresses\Factories\OrderAddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\OrderAddress as LunarOrderAddress;

class OrderAddress extends LunarOrderAddress
{
    use HasCompanyIdentifiersInMeta;
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): OrderAddressFactory
    {
        return OrderAddressFactory::new();
    }
}
