<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;

/**
 * Trait HasIcludes.
 *
 * eg. ?include=users,comments where 'users' and 'comments' are the model's relationships.
 */
trait HasIncludes
{
    public function includes(): array
    {
        return [];
    }

    /**
     * Prepare list of includes to be used in allowedIncludes() to allow a request for a specific relationship.
     */
    public function getIncludes(): array
    {
        return [
            ...collect($this->includes())->map(function (IncludeElement $includeElement) {
                return [
                    $includeElement->getName(),
                    $includeElement->getNameForCount(),
                ];
            })
                ->collapse()
                ->filter()
                ->all(),
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
