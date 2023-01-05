<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions;

use RuntimeException;

abstract class Extension
{
    protected function push(string $property, mixed $values): ExtensionCollection
    {
        if (is_iterable($values)) {
            $this->{$property}->push(...$values);
        }

        if (is_callable($values)) {
            $this->{$property}->push($values);
        }

        return $this->{$property};
    }

    public function __call(string $name, array $arguments): ExtensionCollection
    {
        if (! property_exists($this, $name)) {
            throw new RuntimeException("{$name} can not be extended.");
        }

        return $this->push($name, $arguments[0] ?? null);
    }
}
