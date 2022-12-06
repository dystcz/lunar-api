<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class ProductQuery extends ResourceQuery
{
    /**
     * Get the default include paths to use if the client has provided none.
     *
     * @return string[]|null
     */
    protected function defaultIncludePaths(): ?array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'fields' => [
                'nullable',
                'array',
                JsonApiRule::fieldSets(),
            ],
            'filter' => [
                'nullable',
                'array',
                JsonApiRule::filter()->forget('id'),
            ],
            'include' => [
                'nullable',
                'string',
                JsonApiRule::includePaths(),
            ],
            'page' => JsonApiRule::notSupported(),
            'sort' => JsonApiRule::notSupported(),
            '{{ withCount }}' => [
                'nullable',
                'string',
                JsonApiRule::countable(),
            ],
        ];
    }
}
