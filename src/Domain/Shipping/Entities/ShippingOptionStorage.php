<?php

namespace Dystcz\LunarApi\Domain\Shipping\Entities;

use Generator;
use Illuminate\Support\Collection;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;

class ShippingOptionStorage
{
    private Collection $shippingOptions;

    public function __construct()
    {
        /** @var Cart $cart */
        $cart = CartSession::current();

        $this->shippingOptions = ShippingManifest::getOptions($cart);
    }

    /**
     * Find a shipping option.
     */
    public function find(string $i): ?ShippingOption
    {
        if (isset($this->shippingOptions[$i])) {
            return ShippingOption::fromArray($this->shippingOptions[$i]);
        }

        return null;
    }

    public function cursor(): Generator
    {
        foreach ($this->shippingOptions as $shippingOption) {
            yield ShippingOption::fromArray($shippingOption);
        }
    }

    /**
     * Get all shippingOptions.
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}
