<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Casts;

use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown as PaymentBreakdownValue;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdownItem;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Support\Collection;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;

class PaymentBreakdown implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return PaymentBreakdownValue
     */
    public function get($model, $key, $value, $attributes)
    {
        $breakdown = new PaymentBreakdownValue;

        $breakdown->items = Collection::make(
            json_decode($value, false)
        )->mapWithKeys(function ($payment, $key) {
            $currency = Currency::query()
                ->where('code', $payment->currency->code)
                ->first();

            return [
                $key => new PaymentBreakdownItem(
                    name: $payment->name,
                    identifier: $payment->identifier,
                    price: new Price($payment->value, $currency, 1),
                ),
            ];
        });

        return $breakdown;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  PaymentBreakdownValue  $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, $key, $value, $attributes)
    {
        $breakdownClass = PaymentBreakdownValue::class;

        if ($value && ! is_a($value, $breakdownClass)) {
            throw new \Exception("Payment breakdown must be instance of {$breakdownClass}");
        }

        if (! $value) {
            return [];
        }

        return [
            $key => $value->items->map(function ($item) {
                return [
                    'name' => $item->name,
                    'identifier' => $item->identifier,
                    'value' => $item->price->value,
                    'formatted' => $item->price->formatted,
                    'currency' => $item->price->currency->toArray(),
                ];
            })->toJson(),
        ];
    }

    /**
     * Get the serialized representation of the value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \Illuminate\Support\Collection  $value
     * @param  array<string, mixed>  $attributes
     */
    public function serialize($model, $key, $value, $attributes)
    {
        return json_encode(
            $this->set($model, $key, $value, $attributes)
        );
    }
}
