<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Data;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\Extendable;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;

class ExtensionValue
{
    public function __construct(
        private string|Closure|Relation $value,
    ) {
    }

    /**
     * Create new instance from value.
     */
    public static function from(string|Closure|Relation $value): self
    {
        return new self($value);
    }

    /**
     * Resolve value.
     */
    public function resolve(?Extendable $extendable = null): string|array|Relation
    {
        return $this->value instanceof Closure
            ? ($this->value)($extendable)
            : $this->value;
    }

    /**
     * Get value.
     */
    public function __invoke(): string|Closure
    {
        return $this->value;
    }

    /**
     * Get value.
     */
    public function value(): string|Closure
    {
        return $this->value;
    }
}
