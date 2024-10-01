<?php

namespace Dystcz\LunarApi\Domain\Carts\Concerns;

use Dystcz\LunarApi\Domain\Carts\Actions\SetPaymentOption;
use Dystcz\LunarApi\Domain\Carts\Actions\UnsetPaymentOption;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown;
use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\DataTypes\Price;

trait InteractsWithPaymentOptions
{
    /**
     * The applied payment option.
     */
    public ?PaymentOption $paymentOption = null;

    /**
     * The payment sub total for the cart.
     */
    public ?Price $paymentSubTotal = null;

    /**
     * The payment tax total for the cart.
     */
    public ?Price $paymentTaxTotal = null;

    /**
     * The payment total for the cart.
     */
    public ?Price $paymentTotal = null;

    /**
     * Additional payment estimate meta data.
     */
    public array $paymentEstimateMeta = [];

    /**
     * All the payment breakdowns for the cart.
     */
    public ?PaymentBreakdown $paymentBreakdown = null;

    /**
     * Payment tax breakdown for the cart.
     *
     * @var null|Collection<TaxBreakdown>
     */
    public ?TaxBreakdown $paymentTaxBreakdown = null;

    /**
     * Set the payment option for the cart.
     */
    public function setPaymentOption(PaymentOption $option, bool $refresh = true): Cart
    {
        /** @var Cart $cart */
        $cart = $this;

        foreach (Config::get('lunar.cart.validators.set_payment_option', []) as $action) {
            App::make($action)->using(cart: $cart, paymentOption: $option)->validate();
        }

        return App::make(Config::get('lunar.cart.actions.set_payment_option', SetPaymentOption::class))
            ->execute($cart, $option)
            ->then(fn () => $refresh ? $cart->refresh()->calculate() : $cart);
    }

    /**
     * Unset the payment option from the cart.
     */
    public function unsetPaymentOption(bool $refresh = true): Cart
    {
        /** @var Cart $cart */
        $cart = $this;

        return App::make(Config::get('lunar.cart.actions.unset_payment_option', UnsetPaymentOption::class))
            ->execute($cart)
            ->then(fn () => $refresh ? $cart->refresh()->calculate() : $cart);
    }

    /**
     * Get the payment option for the cart
     */
    public function getPaymentOption(): ?PaymentOption
    {
        /** @var Cart $cart */
        $cart = $this;

        return PaymentManifest::getPaymentOption($cart);
    }
}
