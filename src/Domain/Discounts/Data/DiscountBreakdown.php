<?php

namespace Dystcz\LunarApi\Domain\Discounts\Data;

use Illuminate\Contracts\Support\Arrayable;
use Lunar\Base\ValueObjects\Cart\DiscountBreakdown as LunarDiscountBreakdown;
use Lunar\Base\ValueObjects\Cart\DiscountBreakdownLine;

class DiscountBreakdown implements Arrayable
{
    public function __construct(
        protected LunarDiscountBreakdown $discountBreakdown
    ) {
        //
    }

    public function toArray(): array
    {
        $lines = $this->discountBreakdown->lines->map(function (DiscountBreakdownLine $line) {
            return [
                'line_id' => $line->line->id,
                'quantity' => $line->quantity,
            ];
        });

        return [
            'total' => $this->discountBreakdown->price->decimal,
            'lines' => $lines->toArray(),
        ];
    }
}
