<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

trait HasFields
{
    public array $fields = [];

    public function fields(string $format = 'dotted'): array
    {
        return [
            ...$this->fields,
            ...$this->includesFields($format),
        ];
    }

    protected function includesFields(string $format = 'dotted'): array
    {
        $filters = [];

        foreach ($this->includes as $builderClass) {
            $filters[(new $builderClass::$model)->getTable()] = (new $builderClass())->fields($format);
        }

        if ($format === 'flatten') {
            $filters = $this->flattenValues($filters);
        }

        if ($format === 'dotted') {
            $filters = $this->dottedUsingValues($filters);
        }

        return $filters;
    }

    protected function flattenValues(array $values, ?array &$result = null, ?string $parentKey = null): array
    {
        $result ??= [];

        foreach ($values as $key => $value) {
            $resultKey = $parentKey ?? $key;
            if (! isset($result[$resultKey])) {
                $result[$resultKey] = [];
            }

            if (is_array($value)) {
                $this->flattenValues($value, $result, $key);
            } elseif (! in_array($value, $result[$resultKey])) {
                $result[$resultKey][] = $value;
            }
        }

        return $result;
    }
}
