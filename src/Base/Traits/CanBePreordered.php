<?php

namespace Dystcz\LunarApi\Base\Traits;

use Carbon\Carbon;
use Dystcz\LunarApi\Base\Contracts\Translatable;
use Throwable;

trait CanBePreordered
{
    /**
     * Determine if the model has ETA date set.
     */
    public function hasEtaDate(): bool
    {
        /** @var Translatable $model */
        $model = $this;

        $eta = $model->attr('eta');

        if (! $eta || $eta === '') {
            return false;
        }

        // Check if the ETA date is valid.
        try {
            Carbon::parse($eta);
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }

    /**
     * Determine when model is considered to be preorderable.
     */
    public function isPreorderable(): bool
    {
        return $this->hasEtaDate();
    }
}
