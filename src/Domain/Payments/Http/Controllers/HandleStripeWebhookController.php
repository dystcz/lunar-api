<?php

namespace Dystcz\LunarApi\Domain\Payments\Http\Controllers;

use Dystcz\LunarApi\Domain\Orders\Actions\FindOrderByIntent;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentCanceled;
use Dystcz\LunarApi\Domain\Orders\Events\OrderPaymentFailed;
use Dystcz\LunarApi\Domain\Payments\Actions\AuthorizePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class HandleStripeWebhookController
{
    public function __invoke(
        Request $request,
        AuthorizePayment $authorizePayment
    ): JsonResponse {
        if (config('app.env') !== 'testing') {
            $event = $this->constructEventForNonTestingEnv($request);

            if ($event instanceof JsonResponse) {
                return $event;
            }
        } else {
            $event = Event::constructFrom($request->all());
        }

        $paymentIntent = $event->data->object;
        $order = App::make(FindOrderByIntent::class)($paymentIntent->id);

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $authorizePayment($paymentIntent);
                break;
            case 'payment_intent.canceled':
                OrderPaymentCanceled::dispatch($order);
                break;
            case 'payment_intent.payment_failed':
                OrderPaymentFailed::dispatch($order);
                break;
            default:
                ray('Received unknown event type '.$event->type);
                info('Received unknown event type '.$event->type);
        }

        return response()->json(['message' => 'success']);
    }

    protected function constructEventForNonTestingEnv(Request $request): JsonResponse|Event
    {
        $payload = file_get_contents('php://input');

        try {
            return Webhook::constructEvent(
                $payload,
                $request->header('Stripe-Signature'),
                Config::get('services.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            report($e);

            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            report($e);

            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}
