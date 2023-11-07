<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Sorts;

use LaravelJsonApi\Eloquent\Contracts\SortField;

class InRandomOrder implements SortField
{
    private string $name;

    /**
     * Create a new sort field.
     *
     * @param  string|null  $column
     */
    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * CustomSort constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the sort field.
     */
    public function sortField(): string
    {
        return $this->name;
    }

    /**
     * Apply the sort order to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sort($query, string $direction = 'asc')
    {
        return $query->inRandomOrder();
    }
}
