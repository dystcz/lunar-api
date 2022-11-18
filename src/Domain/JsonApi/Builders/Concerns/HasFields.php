<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

/**
 * Trait HasFields.
 *
 * eg. ?fields[users]=name,email where 'users' is the model's table name.
 */
trait HasFields
{
    public array $fields = [];

    /**
     * Prepare list of fields to be used in allowedFields() to limit fields returned.
     * Returns an array in two possible formats
     * 1. dotted notation: ['users.name', 'users.email']
     * 2. nested array: ['users' => ['name', 'email']]
     */
    public function fields(string $format = 'dotted'): array
    {
        return [
            ...$this->fields,
            ...$this->includesFields($format),
        ];
    }

    /**
     * Get a list of fields from related resources.
     */
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
}
