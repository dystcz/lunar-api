<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

/**
 * Trait HasFilters.
 *
 * eg. ?filter[name]=John
 */
trait HasFilters
{
    public function filters(): array
    {
        return [];
    }

    /**
     * Prepare list of filters to be used in allowedFilters() to filter data with.
     */
    public function getFilters(): array
    {
        return [
            ...$this->filters(),
            ...$this->includesProperty('filters'),
        ];
    }
}
