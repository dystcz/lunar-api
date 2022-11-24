<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\CustomIncludes;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

class AggregateInclude implements IncludeInterface
{
    protected string $column;

    protected string $function;

    public function __construct(string $column, string $function)
    {
        $this->column = $column;

        $this->function = $function;
    }

    public function __invoke(Builder $query, string $relations)
    {
        $query->withAggregate($relations, $this->column, $this->function);
    }
}
