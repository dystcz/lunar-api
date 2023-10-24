<?php

namespace Dystcz\LunarApi\Domain\Countries\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Queries\CollectionQuery;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CountryCollectionQuery extends CollectionQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        // Set the maximum page size to 250, so that the frontend can fetch all countries at once.
        // This is not an issue, because the countries are cached.
        Config::set('lunar-api.pagination.max_size', 250);

        return [
            ...parent::rules(),

            'fields' => [
                'nullable',
                'array',
                JsonApiRule::fieldSets(),
            ],
            'filter' => [
                'nullable',
                'array',
                JsonApiRule::filter(),
            ],
            'include' => [
                'nullable',
                'string',
                JsonApiRule::includePaths(),
            ],
            'page' => [
                'nullable',
                'array',
                JsonApiRule::page(),
            ],
            'sort' => [
                'nullable',
                'string',
                JsonApiRule::sort(),
            ],
            'withCount' => [
                'nullable',
                'string',
                JsonApiRule::countable(),
            ],
        ];
    }
}
