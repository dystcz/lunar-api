<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Concerns;

use Dystcz\LunarApi\Domain\Addresses\Concerns\HasCompanyIdentifiersInMeta;
use Dystcz\LunarApi\Domain\OrderAddresses\Factories\OrderAddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
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
