<?php

namespace Dystcz\LunarApi\Domain\Prices\Actions;

use Dystcz\LunarApi\Domain\TaxZones\Models\TaxZone;
use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;

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

    $priceWithoutVat = new Price(intVal($subTotal / (100 + TaxZone::getDefaultPercentage()) * 100), $currency);

    return $priceWithoutVat;
  }
}
