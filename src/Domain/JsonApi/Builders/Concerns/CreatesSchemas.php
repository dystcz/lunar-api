<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

trait CreatesSchemas
{
    abstract protected function attributesSchema(): array;

    /**
     * Create a relationships schema based on the model's relationships.
     */
    protected function relationshipsSchema(): SchemaContract
    {
        $schemas = [];

        foreach ($this->includes as $relationName => $builderClass) {
            $relation = (new static::$model)->$relationName();

            // Determine the type of the relationship.
            $collection = ! $relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo
                && ! $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne
                && ! $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOneThrough
                && ! $relation instanceof \Illuminate\Database\Eloquent\Relations\MorphOne;

            $dataSchema = Schema::object('data')
                ->properties(
                    Schema::string('id')->example('1'),
                    Schema::string('type')->example($relation->getRelated()->getTable()),
                );

            if (! $collection) {
                // If the relationship returns a single model, we can add the attributes to the schema.
                $schema = Schema::object($relationName)->properties(
                    $dataSchema
                );
            } else {
                // If the relationship returns a collection of models, the data property must be an array of objects.
                $schema = Schema::array($relationName)->items(
                    AllOf::create()->schemas(
                        Schema::object()->properties($dataSchema),
                    )
                );
            }

            $schemas[] = $schema;
        }

        return Schema::object('relationships')->properties(...$schemas);
    }

    /**
     * Create a data schema for the given model with relationships and the model's attributes.
     */
    public function schema(): SchemaContract
    {
        $properties = [
            Schema::string('id')->example('1'),
            Schema::string('type')->example((new static::$model)->getTable()),
            Schema::object('attributes')->properties(
                ...$this->attributesSchema(),
            ),
        ];

        if (! empty($this->includes)) {
            $properties[] = $this->relationshipsSchema();
        }

        return Schema::object(class_basename(static::$model))
            ->properties(
                ...$properties
            );
    }

    /**
     * Generate schema based of JSON API parameters options.
     */
    public function parametersSchema(): array
    {
        return [
            $this->fieldsParametersSchema(),
            $this->sortParametersSchema(),
            $this->filterParametersSchema(),
            $this->includeParametersSchema(),
        ];
    }

    /**
     * Fields parameter schema.
     * See https://jsonapi.org/format/#fetching-sparse-fieldsets.
     * See https://spatie.be/docs/laravel-query-builder/v5/features/selecting-fields.
     */
    protected function fieldsParametersSchema(): Parameter
    {
        $fields = [
            (new static::$model)->getTable() => $this->fields,
            ...$this->includesFields('flatten'),
        ];

        return Parameter::query()
            ->name('fields')
            ->description('Selecting fields')
            ->schema(
                Schema::object()->properties(
                    ...array_map(function ($fields, $table) {
                        return Schema::string($table)->example(implode(',', $fields));
                    }, $fields, array_keys($fields)),
                )
            );
    }

    /**
     * Sort parameter schema.
     * See https://jsonapi.org/format/#fetching-sorting.
     * See https://spatie.be/docs/laravel-query-builder/v5/features/sorting.
     */
    protected function sortParametersSchema(): Parameter
    {
        return Parameter::query()
            ->name('sort')
            ->description('Sorting')
            ->example(implode(',', $this->sorts))
            ->schema(Schema::string());
    }

    /**
     * Filter parameter schema.
     * See https://jsonapi.org/format/#fetching-filtering.
     * See https://spatie.be/docs/laravel-query-builder/v5/features/filtering.
     */
    protected function filterParametersSchema(): Parameter
    {
        return Parameter::query()
            ->name('filter')
            ->description('Filtering')
            ->schema(
                Schema::object()->properties(
                    ...array_map(function ($filter) {
                        return Schema::string($filter);
                    }, $this->filters()),
                )
            );
    }

    /**
     * Include parameter schema.
     * See https://jsonapi.org/format/#fetching-includes.
     * See https://spatie.be/docs/laravel-query-builder/v5/features/including-relationships.
     */
    protected function includeParametersSchema(): Parameter
    {
        return Parameter::query()
            ->name('include')
            ->description('Including relationships')
            ->examples(
                ...array_map(function ($include) {
                    return Example::create($include)->value($include);
                }, $this->includes()),
            )
            ->schema(Schema::string());
    }
}
