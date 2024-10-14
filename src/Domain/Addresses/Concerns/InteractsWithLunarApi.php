<?php

namespace Dystcz\LunarApi\Domain\Addresses\Concerns;

use Dystcz\LunarApi\Domain\Addresses\Factories\AddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HasCompanyIdentifiersInMeta;
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
