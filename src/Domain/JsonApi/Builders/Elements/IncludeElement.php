<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Elements;

class IncludeElement extends Element
{
    public bool $withCount = false;

    public function withCount(): static
    {
        $this->withCount = true;

        return $this;
    }

    public function getNameForCount(): ?string
    {
        if (! $this->withCount) {
            return null;
        }

        return $this->getName().'Count';
    }
}
