<?php

namespace Dystcz\LunarApi\Base\Data;

use ArrayIterator;
use Dystcz\LunarApi\Base\Contracts\Extendable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<mixed,mixed>
 */
class ExtensionValueCollection implements IteratorAggregate
{
    /**
     * Extension values.
     *
     * @param array<ExtensionValue>
     */
    protected array $values = [];

    /**
     * Check if value exists.
     */
    public function exists(string $property): bool
    {
        return isset($this->values[$property]);
    }

    /**
     * Check if values are empty.
     */
    public function empty(): bool
    {
        return empty($this->values);
    }

    /**
     * Get all values
     */
    public function all(): array
    {
        return $this->values;
    }

    /**
     * Get value.
     */
    public function get(string $property): ?ExtensionValue
    {
        return isset($this->values[$property]) ? $this->values[$property] : null;
    }

    /**
     * Set value.
     */
    public function set(string $property, ExtensionValue $value): void
    {
        if (is_null($property)) {
            $this->values[] = $value;
        } else {
            $this->values[$property] = $value;
        }
    }

    /**
     * Push value.
     */
    public function push(ExtensionValue $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * Forget value.
     */
    public function forget(ExtensionValue $value): void
    {
        unset($this->values[$value]);
    }

    /**
     * Get iterator.
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /**
     * Resolve all values.
     *
     * @return array<string, string|array>
     */
    public function resolve(Extendable $extendable = null): array
    {
        return array_map(
            fn (ExtensionValue $value) => $value->resolve($extendable),
            $this->values,
        );
    }
}
