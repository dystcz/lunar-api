<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Validation\Validator;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class CheckOrderPaymentStatusQuery extends ResourceQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array<string,array>
     */
    public function rules(): array
    {
        return [
            'fields' => [
                'nullable',
                'array',
                JsonApiRule::fieldSets([
                    'orders' => [
                        'latest_transaction',
                        'status',
                        'placed_at',
                    ],
                    'transactions' => [
                        'type',
                        'driver',
                        'status',
                        'success',
                        'error',
                    ],
                ]),
            ],
            'include' => [
                'nullable',
                'string',
                JsonApiRule::includePaths([
                    'latest-transaction',
                ]),
            ],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Order $order */
            $order = $this->model();

            $order->load([
                'latestTransaction',
            ]);

            if (false) {
                $validator->errors()->add(
                    'transactions',
                    // TODO: Make translatable
                    'Payment failed'
                );
            }
        });
    }
}
