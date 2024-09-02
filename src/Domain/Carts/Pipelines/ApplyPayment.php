<?php

namespace Dystcz\LunarApi\Domain\Carts\Pipelines;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdownItem;
use Lunar\DataTypes\Price;
use Lunar\Models\Contracts\Cart as CartContract;

class ApplyPayment
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
        $paymentSubTotal = 0;
        $paymentBreakdown = $cart->paymentBreakdown ?: new PaymentBreakdown;
        $paymentOption = $cart->paymentOption ?: $cart->getPaymentOption();

        if ($paymentOption) {
            $paymentBreakdown->items->put(
                $paymentOption->getIdentifier(),
                new PaymentBreakdownItem(
                    name: $paymentOption->getName(),
                    identifier: $paymentOption->getIdentifier(),
                    price: $paymentOption->getPrice(),
                )
            );

            $paymentSubTotal = $paymentOption->getPrice()->value;
            $paymentTotal = $paymentSubTotal;
        }

        $cart->paymentBreakdown = $paymentBreakdown;

        $cart->paymentSubTotal = new Price(
            $paymentBreakdown->items->sum('price.value'),
            $cart->currency,
            1
        );

        return $next($cart);
    }
}
