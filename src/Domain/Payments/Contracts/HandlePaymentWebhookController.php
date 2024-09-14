<?php

namespace Dystcz\LunarApi\Domain\Payments\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface HandlePaymentWebhookController
{
    public function __invoke(string $paymentDriver, Request $request): JsonResponse;
}
