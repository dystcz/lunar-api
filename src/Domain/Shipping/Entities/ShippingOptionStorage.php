<?php

namespace Dystcz\LunarApi\Domain\Shipping\Entities;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Generator;
use Illuminate\Support\Collection;
use Lunar\Facades\ShippingManifest;

class ShippingOptionStorage
{
    private Collection $shippingOptions;

    public function __construct()
    {
        $this->shippingOptions = ShippingManifest::getOptions((new Cart)->newInstance());
    }

    /**
     * Find a site by its slug.
     */
    public function find(string $i): ?ShippingOption
    {
        if (isset($this->shippingOptions[$i])) {
            return ShippingOption::fromArray($this->shippingOptions[$i]);
        }

        return null;
    }

    /**
     * @return Generator
     */
    public function cursor(): Generator
    {
        foreach ($this->shippingOptions as $shippingOption) {
            yield ShippingOption::fromArray($shippingOption);
        }
    }

    /**
     * Get all shippingOptions.
     *
     * @return array
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}