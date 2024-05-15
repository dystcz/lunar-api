<?php

namespace Dystcz\LunarApi\Base\Traits;

use Carbon\Carbon;
use Dystcz\LunarApi\Base\Contracts\Preorderable;

trait CanBePreordered
{
    /**
     * Determine if the model has ETA date set.
     */
    public function hasEtaDate(): bool
    {
        /** @var Preorderable $model */
        $model = $this;

        $eta = $model->attr('eta');

        if (! $eta || $eta === '') {
            return false;
        }

        // Check if the ETA date is valid.
        try {
            Carbon::parse($eta);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
