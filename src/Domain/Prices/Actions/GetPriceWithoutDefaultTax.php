<?php

namespace Dystcz\LunarApi\Domain\Prices\Actions;

use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;
use Lunar\Models\TaxZone;

class GetPriceWithoutDefaultTax
{
    /**
     * Get Price with default tax.
     */
    public function __invoke(Price $price, Purchasable $purchasable): Price
    {
        $price = $price ?? Pricing::for($purchasable)->get()->base->price;
        $currency = $price->currency;
        $subTotal = $price->value;

        $priceWithoutVat = new Price(intval($subTotal / (100 + TaxZone::modelClass()::getDefaultPercentage()) * 100), $currency);

        return $priceWithoutVat;
    }
}
