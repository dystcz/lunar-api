<?php

namespace Dystcz\LunarApi\Domain\Filters\JsonApi\V1\Capabilities;

use Dystcz\LunarApi\Domain\Filters\Entities\FilterStorage;
use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;

class QueryFilters extends QueryAll
{
    private readonly FilterStorage $filters;

    public function __construct(FilterStorage $filters)
    {
        parent::__construct();

        $this->filters = $filters;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): iterable
    {
        return $this->filters->all();
    }
}
