<?php

namespace Dystcz\LunarApi\Domain\Products\Builders;

use Illuminate\Database\Eloquent\Builder;

class ProductBuilder extends Builder
{
    /**
     * Scope a query to only include published models.
     */
    public function published(): self
    {
        return $this->where('status', '!=', 'draft');
    }
}
