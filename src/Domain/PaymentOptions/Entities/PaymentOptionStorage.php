<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Entities;

use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

class PaymentOptionStorage
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    /**
     * @var Collection<PaymentOption>
     */
    private Collection $paymentOptions;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);

        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->paymentOptions = PaymentManifest::getOptions($cart);
    }

    /**
     * Find a payment option.
     */
    public function find(string $i): ?PaymentOption
    {
        if (isset($this->paymentOptions[$i])) {
            return $this->paymentOptions[$i];
        }

        return null;
    }

    /**
     * @return Generator<PaymentOption>
     */
    public function cursor(): Generator
    {
        foreach ($this->paymentOptions as $paymentOption) {
            yield $paymentOption;
        }
    }

    /**
     * Get all payment options.
     *
     * @return PaymentOption[]
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}
