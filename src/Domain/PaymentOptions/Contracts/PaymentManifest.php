<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Contracts;

use Closure;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Illuminate\Support\Collection;
use Lunar\Models\Contracts\Cart as CartContract;

interface PaymentManifest
{
    /**
     * Add a payment option to the manifest.
     */
    public function addOption(PaymentOption $paymentOption): self;

    /**
     * Add a collection of payment options to the manifest.
     *
     * @param  Collection<PaymentOption>  $paymentOptions
     */
    public function addOptions(Collection $paymentOptions): self;

    /**
     * Remove all payment options
     */
    public function clearOptions(): self;

    /**
     * Define closure to retrieve payment option
     *
     * @param  Closure(): void  $closure
     */
    public function getOptionUsing(Closure $closure): self;

    /**
     * Return available options for a given cart.
     */
    public function getOptions(CartContract $cart): Collection;

    /**
     * Return available option for a given cart by identifier.
     */
    public function getOption(CartContract $cart, string $identifier): ?PaymentOption;

    /**
     * Retrieve payment option for a given cart
     */
    public function getPaymentOption(CartContract $cart): ?PaymentOption;
}
