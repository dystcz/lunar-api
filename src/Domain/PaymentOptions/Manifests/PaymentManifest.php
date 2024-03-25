<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Manifests;

use Closure;
use Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentManifest as PaymentManifestContract;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Modifiers\PaymentModifiers;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Lunar\Models\Cart;

class PaymentManifest implements PaymentManifestContract
{
    /**
     * The collection of available shipping options.
     */
    public Collection $options;

    public ?Closure $getOptionUsing = null;

    /**
     * Initiate the class.
     */
    public function __construct()
    {
        $this->options = collect();
    }

    /**
     * {@inheritDoc}
     */
    public function addOption(PaymentOption $option): self
    {
        $exists = $this->options->first(function ($opt) use ($option) {
            return $opt->getIdentifier() == $option->getIdentifier();
        });

        // Does this option already exist?
        if (! $exists) {
            $this->options->push($option);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addOptions(Collection $options): self
    {
        $this->options = $this->options->merge($options);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearOptions(): self
    {
        $this->options = collect();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionUsing(Closure $closure): self
    {
        $this->getOptionUsing = $closure;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions(Cart $cart): Collection
    {
        app(Pipeline::class)
            ->send($cart)
            ->through(
                app(PaymentModifiers::class)->getModifiers()->toArray()
            )->thenReturn();

        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function getOption(Cart $cart, string $identifier): ?PaymentOption
    {
        if (filled($this->getOptionUsing)) {
            $paymentOption = ($this->getOptionUsing)($cart, $identifier);

            if ($paymentOption) {
                return $paymentOption;
            }
        }

        return $this->getOptions($cart)
            ->where('identifier', $identifier)
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentOption(Cart $cart): ?PaymentOption
    {
        if (! $cart->payment_option) {
            return null;
        }

        return PaymentManifest::getOption($cart, $cart->payment_option);
    }
}
