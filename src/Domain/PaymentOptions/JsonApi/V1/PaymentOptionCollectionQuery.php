<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery;

class PaymentOptionCollectionQuery extends CollectionQuery
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
