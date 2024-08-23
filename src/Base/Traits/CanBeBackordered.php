<?php

namespace Dystcz\LunarApi\Base\Traits;

use Dystcz\LunarApi\Base\Enums\PurchasableStatus;

trait CanBeBackordered
{
    /**
     * Determine when model is considered to be backorderable.
     */
    public function isBackorderable(): bool
    {
        return $this->backorder > 0 && $this->purchasable === PurchasableStatus::BACKORDER->value;
    }
}
