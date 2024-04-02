<?php

namespace Dystcz\LunarApi\Domain\Carts\ValueObjects;

use Illuminate\Support\Collection;
use Stringable;

class PaymentBreakdown implements Stringable
{
    public function __construct(
        public ?Collection $items = null
    ) {
        $this->items = $items ?: Collection::make();
    }

    /**
     * Cast the payment breakdown to a JSON string.
     */
    public function __toString(): string
    {
        return $this->items->toJson();
    }
}
