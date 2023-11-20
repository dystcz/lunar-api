<?php

namespace Dystcz\LunarApi\Domain\Discounts\Data;

use Illuminate\Contracts\Support\Arrayable;
use Lunar\Base\ValueObjects\Cart\DiscountBreakdown as LunarDiscountBreakdown;

class DiscountBreakdown implements Arrayable
{
    public function __construct(
        protected LunarDiscountBreakdown $discountBreakdown
    ) {
        //
    }

    public function toArray(): array
    {
        /** \Lunar\DataTypes\Price $price */
        $price = $this->discountBreakdown->price;

        // TODO: Finish mapping
        return [
            'total' => $price->decimal,
        ];
    }
}
