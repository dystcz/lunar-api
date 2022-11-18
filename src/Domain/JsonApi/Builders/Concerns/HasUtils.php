<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

trait HasUtils
{
    protected function prependKey(string $key, array $values): array
    {
        $prepended = [];

        foreach ($values as $value) {
            if (is_array($value)) {
                $prepended = [
                    ...$prepended,
                    ...$this->prependKey($key, $value),
                ];
            } else {
                $prepended[] = $key.'.'.$value;
            }
        }

        return $prepended;
    }

    protected function dottedUsingValues(array $fields): array
    {
        $dotted = [];

        foreach ($fields as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $dottedIncludes = $this->dottedUsingValues($value);

                $dotted = [
                    ...$this->prependKey($key, $dottedIncludes),
                    ...$dotted,
                ];
            } else {
                $dotted[] = $value;
            }
        }

        return $dotted;
    }

    protected function includesProperty(string $property, string $format = 'dotted'): array
    {
        $filters = [];

        foreach ($this->includes as $relationName => $builderClass) {
            $filters[$relationName] = (new $builderClass())->$property();
        }

        if ($format === 'dotted') {
            $filters = $this->dottedUsingValues($filters);
        }

        $filters = array_filter($filters, fn ($filter) => ! empty($filter));

        return $filters;
    }
}
