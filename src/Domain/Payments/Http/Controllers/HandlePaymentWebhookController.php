<?php

namespace Dystcz\LunarApi\Domain\Payments\Http\Controllers;

use Dystcz\LunarApi\Domain\Payments\Actions\AuthorizePaypalPayment;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandlePaymentWebhookController
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {
    }

    public function __invoke(
        string $paymentDriver,
        Request $request,
        AuthorizePaypalPayment $authorizePayment
    ): JsonResponse {
        $payment = $this->register->get($paymentDriver);

        return $payment->handleWebhook($request);
    }
}
