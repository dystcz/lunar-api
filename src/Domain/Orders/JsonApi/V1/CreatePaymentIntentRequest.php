<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CreatePaymentIntentRequest extends ResourceRequest
{
    // Hack to add a field to the schema on the fly
    protected function prepareForValidation(): void
    {
        SchemaManifest::for(OrderSchema::class)
            ->setFields([Str::make('payment_method')]);
    }

    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        return [
            'payment_method' => [
                'required',
                'string',
                Rule::in(array_keys(config('lunar.payments.types'))),
            ],
        ];
    }
}
