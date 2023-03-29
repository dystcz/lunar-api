<?php

namespace Dystcz\LunarApi\Domain\Prices\Actions;

use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;
use Lunar\Facades\Taxes;

class GetPriceWithDefaultTax
{
    /**
     * Get Price with default tax.
     */
    public function __invoke(Purchasable $purchasable, Price $price): Price
    {
        $price = $price ?? Pricing::for($purchasable)->get()->base->price;
        $currency = $price->currency;
        $subTotal = $price->value;

        $taxBreakDown = Taxes::setCurrency($currency)
            ->setPurchasable($purchasable)
            ->getBreakdown($subTotal);

        $tax = $taxBreakDown->amounts->sum('price.value');

        $priceWithVat = new Price($subTotal + $tax, $currency);

        return $priceWithVat;
    }
}
