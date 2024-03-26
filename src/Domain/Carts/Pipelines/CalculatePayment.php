<?php

namespace Dystcz\LunarApi\Domain\Carts\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\Base\ValueObjects\Cart\TaxBreakdownAmount;
use Lunar\DataTypes\Price;
use Lunar\Facades\Taxes;

class CalculatePayment
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(Cart): void  $next
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        $paymentOption = $cart->paymentOption ?: PaymentManifest::getPaymentOption($cart);

        if (! $paymentOption) {
            return $next($cart);
        }

        $paymentSubTotal = $cart->paymentBreakdown->items->sum('price.value');

        $paymentTax = Taxes::setShippingAddress($cart->shippingAddress)
            ->setCurrency($cart->currency)
            ->setPurchasable($paymentOption)
            ->getBreakdown($paymentSubTotal);

        $paymentTaxTotal = $paymentTax->amounts->sum('price.value');
        $paymentTaxTotal = new Price($paymentTaxTotal, $cart->currency, 1);

        $taxTotal = $cart->taxTotal->value + $paymentTaxTotal?->value;

        $paymentTotal = $paymentSubTotal;

        if (! prices_inc_tax()) {
            $paymentTotal += $paymentTaxTotal?->value;
        }

        $cart->taxTotal = new Price($taxTotal, $cart->currency, 1);

        $cart->taxBreakdown = new TaxBreakdown(
            $paymentTax->amounts->groupBy('identifier')->map(function ($amounts) use ($cart) {
                return new TaxBreakdownAmount(
                    price: new Price($amounts->sum('price.value'), $cart->currency, 1),
                    percentage: $amounts->first()->percentage,
                    description: $amounts->first()->description,
                    identifier: $amounts->first()->identifier
                );
            })
        );

        $paymentTotal = new Price($paymentTotal, $cart->currency, 1);
        $cart->paymentTotal = $paymentTotal;
        $cart->paymentTaxTotal = $paymentTaxTotal;

        $total = $cart->total->value + $cart->paymentTotal?->value;
        $cart->total = new Price($total, $cart->currency, 1);

        // Call the modify cart closure if it exists.
        if (is_callable($paymentOption->modifyCart)) {
            $cart = ($paymentOption->modifyCart)($cart, $paymentOption);
        }

        return $next($cart);
    }
}
