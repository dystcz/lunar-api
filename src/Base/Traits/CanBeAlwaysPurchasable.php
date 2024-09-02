<?php

namespace Dystcz\LunarApi\Base\Traits;

use Dystcz\LunarApi\Base\Enums\PurchasableStatus;

trait CanBeAlwaysPurchasable
{
    /**
     * Determine when model is considered to be always purchasable.
     */
    public function isAlwaysPurchasable(): bool
    {
        return $this->purchasable === PurchasableStatus::ALWAYS->value;
    }
}
