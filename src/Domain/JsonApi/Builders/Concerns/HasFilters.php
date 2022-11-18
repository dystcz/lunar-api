<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

/**
 * Trait HasFilters.
 *
 * eg. ?filter[name]=John
 */
trait HasFilters
{
    public array $filters = [];

    /**
     * Prepare list of filters to be used in allowedFilters() to filter data with.
     */
    public function filters(): array
    {
        return [
            ...$this->filters,
            ...$this->includesProperty('filters'),
        ];
    }
}
