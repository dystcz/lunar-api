<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

/**
 * Trait HasIcludes.
 *
 * eg. ?include=users,comments where 'users' and 'comments' are the model's relationships.
 */
trait HasIncludes
{
    public array $includes = [];

    /**
     * Prepare list of includes to be used in allowedIncludes() to allow a request for a specific relationship.
     */
    public function includes(): array
    {
        return [
            ...array_keys($this->includes),
            ...$this->includesIncludes(),
        ];
    }

    /**
     * Get a list of includes from related resources.
     * The returned array is cleared of empty values and sorted.
     */
    protected function includesIncludes(): array
    {
        $filters = $this->includesProperty('includes');

        return collect($filters)
            ->filter(fn ($value) => ! empty($value))
            ->sort()
            ->all();
    }
}
