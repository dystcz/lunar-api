<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Concerns;

use Dystcz\LunarApi\Domain\Addresses\Concerns\HasCompanyIdentifiersInMeta;
use Dystcz\LunarApi\Domain\CartAddresses\Factories\CartAddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HasCompanyIdentifiersInMeta;
    use HashesRouteKey;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CartAddressFactory
    {
        return CartAddressFactory::new();
    }

    /**
     * Check if the cart address has a shipping option .
     */
    public function hasShippingOption(): bool
    {
        return (bool) $this->shipping_option;
    }
}
