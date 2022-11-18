<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

trait HasIncludes
{
    public array $includes = [];

    public function includes(): array
    {
        return [
            ...array_keys($this->includes),
            ...$this->includesIncludes(),
        ];
    }

    protected function includesIncludes(): array
    {
        $filters = $this->includesProperty('includes');

        return collect($filters)
            ->filter(fn ($value) => ! empty($value))
            ->sort()
            ->all();
    }
}
