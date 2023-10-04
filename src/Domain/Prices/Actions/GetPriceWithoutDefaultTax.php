<?php

namespace Dystcz\LunarApi\Domain\Prices\Actions;

use Dystcz\LunarApi\Domain\TaxZones\Models\TaxZone;
use Lunar\Base\Purchasable;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;
use Lunar\Facades\Taxes;

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

    $taxBreakDown = Taxes::setCurrency($currency)
      ->setPurchasable($purchasable)
      ->getBreakdown($subTotal);

    $tax = $taxBreakDown->amounts->sum('price.value');

    $priceWithVat = new Price(intVal($subTotal / (100 + TaxZone::getDefaultPercentage()) * 100), $currency);

    return $priceWithVat;
  }
}
