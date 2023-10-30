<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Models;

use Dystcz\LunarApi\Domain\Addresses\Traits\HasCompanyIdentifiersInMeta;
use Dystcz\LunarApi\Domain\CartAddresses\Factories\CartAddressFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\CartAddress as LunarCartAddress;

class CartAddress extends LunarCartAddress
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
