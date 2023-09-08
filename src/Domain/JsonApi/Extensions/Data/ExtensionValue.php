<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Data;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\Extendable;
use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Contracts\Schema\Relation;
use LaravelJsonApi\Eloquent\Contracts\SortField;

class ExtensionValue
{
    public function __construct(
        private string|Closure|Relation|Field|Filter|SortField $value,
    ) {
    }

    /**
     * Create new instance from value.
     */
    public static function from(string|Closure|Relation|Field|Filter|SortField $value): self
    {
        return new self($value);
    }

    /**
     * Resolve value.
     */
    public function resolve(Extendable $extendable = null): string|array|Relation|Field|Filter
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
