<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Sorts;

use Dystcz\LunarApi\Domain\Products\ProductViews;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Eloquent\Contracts\SortField;

class RecentlyViewedSort implements SortField
{
    private readonly string $name;

    /**
     * Create a new sort field.
     *
     * @param  string|null  $column
     * @return CustomSort
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
     * @param  Builder  $query
     * @return Builder
     */
    public function sort($query, string $direction = 'asc')
    {
        $list = App::get(ProductViews::class)->sorted();

        if (! empty($list)) {
            $query->orderByRaw('FIELD(id, '.implode(',', $list).')');
        }
    }
}
