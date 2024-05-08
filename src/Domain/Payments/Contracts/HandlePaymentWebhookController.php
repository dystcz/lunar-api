<?php

namespace Dystcz\LunarApi\Domain\Payments\Contracts;

use Dystcz\LunarApi\Base\Contracts\Swappable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface HandlePaymentWebhookController extends Swappable
{
    public function __invoke(string $paymentDriver, Request $request): JsonResponse;
}
