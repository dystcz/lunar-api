<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class ProductQuery extends ResourceQuery
{
    /**
     * The default include paths to use if the client provides none.
     *
     * @var array|null
     */
    protected ?array $defaultIncludePaths = ['urls'];

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
