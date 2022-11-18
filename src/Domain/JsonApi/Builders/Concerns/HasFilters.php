<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

trait HasFilters
{
    public array $filters = [];

    public function filters(): array
    {
        return [
            ...$this->filters,
            ...$this->includesProperty('filters'),
        ];
    }
}
