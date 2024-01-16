<?php

namespace Dystcz\LunarApi\Domain\Filters\Data;

use Illuminate\Contracts\Support\Arrayable;

class FilterOption implements Arrayable
{
    public function __construct(
        public string $label,
        public string $value,
        public int $count = 0,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'count' => $this->count,
        ];
    }
}
