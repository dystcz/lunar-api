<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\CreatePaymentIntentRequest;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\CreatePaypalPaymentIntent;
use Dystcz\LunarApi\Domain\Payments\Actions\CreateStripePaymentIntent;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Responses\DataResponse;

class CreatePaymentIntentController extends Controller
{
    public function createPaymentIntent(
        OrderSchema $schema,
        CreatePaymentIntentRequest $request,
        Order $order,
        CreateStripePaymentIntent $createStripePaymentIntent,
        CreatePaypalPaymentIntent $createPaypalPaymentIntent
    ) {
        $this->authorize('update', $order);

        $paymentMethod = $request->validated()['payment_method'];

        if (! array_key_exists($paymentMethod, Config::get('lunar.payments.types'))) {
            report('Payment method ['.$paymentMethod.'] is not supported');

            return DataResponse::make($order)->withMeta(['payment_intent' => null]);
        }

        if ($paymentMethod === 'card') {
            $intent = $createStripePaymentIntent($order->cart);
        } elseif ($paymentMethod === 'paypal') {
            $intent = $createPaypalPaymentIntent($order->cart);
        }

        return DataResponse::make($order)
            ->withMeta(['payment_intent' => $intent->toArray()]);
    }
}
