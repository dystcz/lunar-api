<?php

namespace Dystcz\LunarApi\Domain\Transactions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Queries\Query;

class TransactionQuery extends Query
{
    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
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
