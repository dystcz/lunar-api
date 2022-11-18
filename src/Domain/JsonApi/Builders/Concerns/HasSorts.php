<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

trait HasSorts
{
    public array $sorts = [];

    public function sorts(): array
    {
        return [
            ...$this->sorts,
            ...$this->includesProperty('sorts'),
            // ...$this->includesSorts() // does not seem to be supported by spatie query builder
        ];
    }
}
