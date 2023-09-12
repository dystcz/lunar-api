<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\CreatePaymentIntentRequest;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\CreatePaymentIntent;
use LaravelJsonApi\Core\Responses\DataResponse;
use RuntimeException;

class CreatePaymentIntentController extends Controller
{
    public function createPaymentIntent(
        CreatePaymentIntentRequest $request,
        Order                      $order,
        CreatePaymentIntent        $createPaymentIntent,
    )
    {
        $this->authorize('update', $order);

        $paymentMethod = $request->validated()['payment_method'];

        try {
            $intent = $createPaymentIntent($paymentMethod, $order->cart);
        } catch (RuntimeException $e) {
            return DataResponse::make($order)->withMeta(['payment_intent' => null]);
        }

        return DataResponse::make($order)
            ->withMeta(['payment_intent' => $intent->toArray()]);
    }
}
