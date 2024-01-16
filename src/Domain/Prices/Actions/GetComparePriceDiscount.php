<?php

namespace Dystcz\LunarApi\Domain\Prices\Actions;

use Lunar\DataTypes\Price;

class GetComparePriceDiscount
{
    public function __construct(
        protected Price $price,
        protected Price $comparePrice,
    ) {
        //
    }

    /**
     * Get raw discount percentage from price and compare price.
     */
    public function raw(): float
    {
        if ($this->comparePrice->value <= $this->price->value) {
            return 0;
        }

        return (($this->comparePrice->value - $this->price->value) / $this->comparePrice->value) * 100;
    }

    /**
     * Get discount percentage decimal.
     */
    public function decimal(): float
    {
        if ($this->comparePrice->value <= $this->price->value) {
            return 0;
        }

        return number_format($this->raw(), 2);
    }

    /**
     * Get discount percentage integer value.
     */
    public function value(): string
    {
        return intval($this->raw());
    }

    /**
     * Get formatted discount percentage.
     */
    public function formatted(): string
    {
        return $this->value().'%';
    }

    /**
     * Check if price is discounted.
     */
    public function isOnSale(): bool
    {
        return $this->raw() > 0;
    }
}
