<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class CreatePaymentIntentRequest extends ResourceRequest
{
    // Hack to add a field to the schema on the fly
    protected function prepareForValidation(): void
    {
        /** @var SchemaExtension $orderSchemaExtension */
        $orderSchemaExtension = SchemaManifest::for(OrderSchema::class);

        $orderSchemaExtension->setFields([
            Str::make('payment_method'),
            ArrayList::make('meta'),
        ]);
    }

    /**
     * Get the validation rules for the resource.
     */
    public function rules(): array
    {
        $paymentTypes = Config::get('lunar.payments.types');

        return [
            'meta' => 'array',
            'payment_method' => [
                'required',
                'string',
                Rule::in(array_keys($paymentTypes)),
            ],
        ];
    }
}
