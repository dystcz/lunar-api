<?php

namespace Dystcz\LunarApi\Domain\Payments\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Payments\Contracts\HandlePaymentWebhookController as HandlePaymentWebhookControllerContract;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandlePaymentWebhookController extends Controller implements HandlePaymentWebhookControllerContract
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {}

    public function __invoke(
        string $paymentDriver,
        Request $request,
    ): JsonResponse {
        $paymentAdapter = $this->register->get($paymentDriver);

        return $paymentAdapter->handleWebhook($request);
    }
}
