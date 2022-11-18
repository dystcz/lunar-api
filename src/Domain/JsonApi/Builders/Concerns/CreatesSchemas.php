<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

trait CreatesSchemas
{
    abstract protected function attributesSchema(): array;

    protected function relationshipsSchema(): SchemaContract
    {
        $schemas = [];

        foreach ($this->includes as $relationName => $builderClass) {
            $relation = (new static::$model)->$relationName();

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
                $schema = Schema::object($relationName)->properties(
                    $dataSchema
                    // Schema::array('meta')->items(),
                    // Schema::array('links')->items(),
                );
            } else {
                $schema = Schema::array($relationName)->items(
                    \GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf::create()->schemas(
                        Schema::object()->properties($dataSchema),
                    )
                );
            }

            $schemas[] = $schema;
        }

        return Schema::object('relationships')->properties(...$schemas);
    }

    public function schema(): SchemaContract
    {
        $properties = [
            Schema::string('id')->example('1'),
            Schema::string('type')->example((new static::$model)->getTable()),
            Schema::object('attributes')->properties(
                ...$this->attributesSchema(),
            ),
            // Schema::array('meta')->items(),
            // Schema::array('links')->items(),
        ];

        if (! empty($this->includes)) {
            $properties[] = $this->relationshipsSchema();
        }

        return Schema::object(class_basename(static::$model))
            ->properties(
                ...$properties
            );
    }

    public function parametersSchema(): array
    {
        return [
            $this->fieldsParametersSchema(),
            $this->sortParametersSchema(),
            $this->filterParametersSchema(),
            $this->includeParametersSchema(),
        ];
    }

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

    protected function sortParametersSchema(): Parameter
    {
        return Parameter::query()
            ->name('sort')
            ->description('Sorting')
            ->example(implode(',', $this->sorts))
            ->schema(Schema::string());
    }

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
