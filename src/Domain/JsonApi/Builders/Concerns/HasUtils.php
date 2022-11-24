<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\JsonApi\Builders\JsonApiBuilder;
use Illuminate\Support\Str;

trait HasUtils
{
    /**
     * Prepend the given string to the given values.
     */
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

    /**
     * Merges the given values into a single string using dot.
     * eg. ['users' => ['posts' => 'comments']] => ['users.posts.comments'].
     */
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

    /**
     * Get a list of values for provided property from related resources.
     */
    protected function includesProperty(string $property, string $format = 'dotted'): array
    {
        $filters = [];

        /** @var IncludeElement $includeElement */
        foreach ($this->includes() as $includeElement) {
            $relationName = $includeElement->getName();
            $filters[$relationName] = $includeElement->getBuilder()->{'get'.Str::ucfirst($property)}();
        }

        if ($format === 'dotted') {
            $filters = $this->dottedUsingValues($filters);
        }

        $filters = array_filter($filters, fn ($filter) => ! empty($filter));

        return $filters;
    }

    /**
     * Group values under a key.
     * eg. ['users' => ['name', 'posts' => ['title']] => ['users' => ['name'], 'posts' => ['title']].
     */
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

    public function isBuilderClass($value): bool
    {
        return is_string($value) && class_exists($value) && is_subclass_of($value, JsonApiBuilder::class);
    }
}
