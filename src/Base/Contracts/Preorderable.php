<?php

namespace Dystcz\LunarApi\Base\Contracts;

interface Preorderable extends Translatable
{
    /**
     * Determine if the model has ETA date set.
     */
    public function hasEtaDate(): bool;
}
