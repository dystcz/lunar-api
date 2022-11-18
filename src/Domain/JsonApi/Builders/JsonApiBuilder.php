<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Spatie\QueryBuilder\QueryBuilder;

abstract class JsonApiBuilder
{
    use Concerns\HasFields;
    use Concerns\HasSorts;
    use Concerns\HasFilters;
    use Concerns\HasIncludes;
    use Concerns\HasUtils;
    use Concerns\CreatesSchemas;

    public static string $model;

    public static string $schema;

    public function query(): QueryBuilder
    {
        return QueryBuilder::for(static::$model)
            ->allowedFields($this->fields())
            ->allowedSorts($this->sorts())
            ->allowedFilters($this->filters())
            ->allowedIncludes($this->includes());
    }
}
