<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Contracts;

interface FilterCollection
{
    /**
     * Get filters to be registered.
     *
     * @return array
     */
    public function toArray(): array;
}
