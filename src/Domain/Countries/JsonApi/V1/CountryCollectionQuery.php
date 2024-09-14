<?php

namespace Dystcz\LunarApi\Domain\Countries\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Queries\CollectionQuery;
use Illuminate\Support\Facades\Config;

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
        // Config::set('lunar-api.general.pagination.max_size', 250);

        return [
            ...parent::rules(),

            // TODO: Check if this works
            'page.size' => [
                'nullable',
                'integer',
                'between:1,250',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            ...parent::messages(),
        ];
    }
}
