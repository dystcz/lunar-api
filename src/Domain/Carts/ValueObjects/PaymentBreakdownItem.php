<?php

namespace Dystcz\LunarApi\Domain\Carts\ValueObjects;

use Lunar\DataTypes\Price;

class PaymentBreakdownItem
{
    public function __construct(
        public string $name,
        public string $identifier,
        public Price $price
    ) {
        //
    }
}
