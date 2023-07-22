<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\CreatePaymentIntentRequest;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\CreatePaymentIntent;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Responses\DataResponse;

class CreatePaymentIntentController extends Controller
{
    public function createPaymentIntent(
        OrderSchema $schema,
        CreatePaymentIntentRequest $request,
        Order $order,
    ) {
        $this->authorize('update', $order);

        $paymentMethod = $request->validated()['payment_method'];

        if (! array_key_exists($paymentMethod, Config::get('lunar.payments.types'))) {
            report('Payment method ['.$paymentMethod.'] is not supported');

            return DataResponse::make($order)->withMeta(['payment_intent' => null]);
        }

        /**
         * @var array<string, string> $paymentMethodIntentProviders
         */
        $paymentMethodIntentProviders = Config::get('lunar-api.payment_intent_providers', []);

        if (! array_key_exists($paymentMethod, $paymentMethodIntentProviders)) {
            report('Payment method provider ['.$paymentMethod.'] is not defined');

            return DataResponse::make($order)->withMeta(['payment_intent' => null]);
        }

        $createPaymentIntentClass = $paymentMethodIntentProviders[$paymentMethod];

        $meta = (object) $request->validated()['meta'];
        /**
         * @var CreatePaymentIntent $createPaymentIntent
         */
        $createPaymentIntent = new $createPaymentIntentClass($order->cart, $meta);

        $intent = $createPaymentIntent->execute();

        return DataResponse::make($order)
            ->withMeta(['payment_intent' => $intent]);
    }
}
