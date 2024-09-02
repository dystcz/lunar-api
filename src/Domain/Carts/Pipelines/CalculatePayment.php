<?php

namespace Dystcz\LunarApi\Domain\Carts\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\Base\ValueObjects\Cart\TaxBreakdownAmount;
use Lunar\DataTypes\Price;
use Lunar\Facades\Taxes;
use Lunar\Models\Contracts\Cart as CartContract;

class CalculatePayment
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(CartContract): void  $next
     * @return Closure
     */
    public function handle(CartContract $cart, Closure $next): mixed
    {
        /** @var Cart $cart */
        $paymentOption = $cart->paymentOption ?: $cart->getPaymentOption();

        if (! $paymentOption) {
            return $next($cart);
        }

        $taxBreakDownAmounts = $cart->taxBreakdown->amounts->filter()->flatten();

        $paymentSubTotal = $cart->paymentBreakdown->items->sum('price.value');

        $paymentTaxBreakdown = Taxes::setShippingAddress($cart->shippingAddress)
            ->setCurrency($cart->currency)
            ->setPurchasable($paymentOption)
            ->getBreakdown($paymentSubTotal);

        $paymentTaxTotal = $paymentTaxBreakdown->amounts->sum('price.value');
        $paymentTaxTotal = new Price($paymentTaxTotal, $cart->currency, 1);

        $taxTotal = $cart->taxTotal->value + $paymentTaxTotal?->value;

        $taxBreakDownAmounts = $taxBreakDownAmounts->merge(
            $paymentTaxBreakdown->amounts
        );

        $paymentTotal = $paymentSubTotal;

        if (! prices_inc_tax()) {
            $paymentTotal += $paymentTaxTotal?->value;
        }

        $cart->paymentTaxBreakdown = $paymentTaxBreakdown;
        $cart->taxTotal = new Price($taxTotal, $cart->currency, 1);

        $cart->taxBreakdown = new TaxBreakdown(
            $taxBreakDownAmounts->groupBy('identifier')->map(function ($amounts) use ($cart) {
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
