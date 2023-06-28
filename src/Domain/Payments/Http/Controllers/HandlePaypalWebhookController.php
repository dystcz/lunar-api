<?php

namespace Dystcz\LunarApi\Domain\Payments\Http\Controllers;

use Config;
use Dystcz\LunarApi\Domain\Orders\Actions\FindOrderByIntent;
use Dystcz\LunarApi\Domain\Payments\Actions\AuthorizePaypalPayment;
use Dystcz\LunarPaypal\Actions\VerifyWebhookSignature;
use Dystcz\LunarPaypal\Exceptions\InvalidWebhookSignatureException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HandlePaypalWebhookController
{
    public function __invoke(
        Request $request,
        AuthorizePaypalPayment $authorizePayment
    ): JsonResponse {
        if ($request->event_type !== 'CHECKOUT.ORDER.APPROVED') {
            return response()->json();
        }

        try {
            if (Config::get('lunar.paypal.mode') !== 'sandbox') {
                App::make(VerifyWebhookSignature::class)(
                    body: $request->all(),
                    headers: $request->headers->all()
                );
            }
        } catch (InvalidWebhookSignatureException $e) {
            // Invalid signature
            report($e);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $order = App::make(FindOrderByIntent::class)(
            $request->get('resource')['id']
        );

        if (! $order) {
            report('Order not found for payment intent: '.$request->get('resource')['id']);

            return response()->json(['error' => 'Order not found'], 404);
        }

        $authorizePayment($order);

        return response()->json(['message' => 'success']);
    }
}
