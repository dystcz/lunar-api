<?php

namespace Dystcz\LunarApi\Base\Traits;

use Dystcz\LunarApi\Base\Contracts\HasAvailabilityStatus;
use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait InteractsWithAvailability
{
    use CanBeAlwaysPurchasable;
    use CanBeBackordered;
    use CanBeInStock;
    use CanBePreordered;

    /**
     * Get availability.
     */
    public function getAvailability(): Availability
    {
        /** @var HasAvailabilityStatus $this */
        return Availability::of($this);
    }

    /**
     * Get availability attribute.
     */
    public function availability(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getAvailability()
        );
    }

    /**
     * Prepare model for availability evaluation.
     */
    public function prepareModelForAvailabilityEvaluation(): void
    {
        //
    }
}
