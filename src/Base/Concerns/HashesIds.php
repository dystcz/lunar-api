<?php

namespace Dystcz\LunarApi\Base\Concerns;

trait HashesIds
{
    protected bool $hashIds = false;

    public function hashIds(bool $value): static
    {
        $this->hashIds = $value;

        return $this;
    }

    public function usesHashids(): bool
    {
        return $this->hashIds;
    }
}
