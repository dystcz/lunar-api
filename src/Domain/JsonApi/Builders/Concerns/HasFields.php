<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;

/**
 * Trait HasFields.
 *
 * eg. ?fields[users]=name,email where 'users' is the model's table name.
 */
trait HasFields
{
    public function fields(): array
    {
        return [];
    }

    /**
     * Prepare list of fields to be used in allowedFields() to limit fields returned.
     * Returns an array in two possible formats
     * 1. dotted notation: ['users.name', 'users.email']
     * 2. nested array: ['users' => ['name', 'email']].
     */
    public function getFields(string $format = 'dotted'): array
    {
        return [
            ...$this->fields(),
            ...$this->includesFields($format),
        ];
    }

    /**
     * Get a list of fields from related resources.
     */
    protected function includesFields(string $format = 'dotted'): array
    {
        $filters = [];

        /** @var IncludeElement $includeElement */
        foreach ($this->includes() as $includeElement) {
            $filters[(new ($includeElement->getBuilderClass()::$model))->getTable()] = $includeElement->getBuilder()->fields($format);
        }

        if ($format === 'flatten') {
            $filters = $this->flattenValues($filters);
        }

        if ($format === 'dotted') {
            $filters = $this->dottedUsingValues($filters);
        }

        return $filters;
    }
}
