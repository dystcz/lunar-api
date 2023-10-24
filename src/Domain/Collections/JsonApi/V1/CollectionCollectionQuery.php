<?php

namespace Dystcz\LunarApi\Domain\Collections\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Queries\CollectionQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CollectionCollectionQuery extends CollectionQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
     */
    public function rules(): array
    {
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
