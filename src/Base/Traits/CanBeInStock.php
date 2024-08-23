<?php

namespace Dystcz\LunarApi\Base\Traits;

use Dystcz\LunarApi\Base\Enums\PurchasableStatus;

trait CanBeInStock
{
    /**
     * Determine when model is considered to be in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0 && $this->purchasable === PurchasableStatus::IN_STOCK->value;
    }
}
