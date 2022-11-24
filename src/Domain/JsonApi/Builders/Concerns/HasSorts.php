<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

/**
 * Trait HasSorts.
 *
 * eg. ?sort=name
 */
trait HasSorts
{
    public function sorts(): array
    {
        return [];
    }

    /**
     * Prepare list of sorts to be used in allowedSorts() to sort results.
     */
    public function getSorts(): array
    {
        return [
            ...$this->sorts(),
            // ...$this->includesProperty('sorts'), // does not seem to be supported by spatie query build
        ];
    }
}
