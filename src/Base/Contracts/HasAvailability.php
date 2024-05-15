<?php

namespace Dystcz\LunarApi\Base\Contracts;

use Dystcz\LunarApi\Domain\Products\Enums\Availability;

interface HasAvailability extends Translatable
{
    /**
     * Get availability.
     */
    public function getAvailability(): Availability;

    /**
     * Determine when model is considered to be always purchasable.
     */
    public function isAlwaysPurchasable(): bool;

    /**
     * Determine when model is considered to be in stock.
     */
    public function isInStock(): bool;

    /**
     * Determine if the model has ETA date set.
     */
    public function hasEtaDate(): bool;

    /**
     * Determine when model is considered to be preorderable.
     */
    public function isPreorderable(): bool;

    /**
     * Determine when model is considered to be backorderable.
     */
    public function isBackorderable(): bool;

    /**
     * Prepare model for availability evaluation.
     */
    public function prepareModelForAvailabilityEvaluation(): void;
}
