<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\Entities;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Facades\ShippingManifest;

class ShippingOptionStorage
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    private Collection $shippingOptions;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);

        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->shippingOptions = ShippingManifest::getOptions($cart);
    }

    /**
     * Find a shipping option.
     */
    public function find(string $id): ?ShippingOption
    {
        if (isset($this->shippingOptions[$id])) {
            return ShippingOption::fromOption($this->shippingOptions[$id]);
        }

        return null;
    }

    /**
     * @return Generator<ShippingOption>
     */
    public function cursor(): Generator
    {
        foreach ($this->shippingOptions as $shippingOption) {
            yield ShippingOption::fromOption($shippingOption);
        }
    }

    /**
     * Get all shippingOptions.
     *
     * @return ShippingOption[]
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}
