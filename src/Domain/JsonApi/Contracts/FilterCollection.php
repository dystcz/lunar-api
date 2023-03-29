<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Contracts;

interface FilterCollection
{
    /**
     * Get filters to be registered.
     */
    public function toArray(): array;
}
