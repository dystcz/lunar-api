<?php

namespace Dystcz\LunarApi\Tests\Stubs\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentIntent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Cart;

class TestPaymentAdapter extends PaymentAdapter
{
    public function createIntent(Cart $cart, array $meta = [], int $amount = null): PaymentIntent
    {
        $paymentIntent = new PaymentIntent(
            id: 1,
            amount: $amount ?? 500,
            status: 'intent',
        );

        $this->createTransaction($cart, $paymentIntent);

        return $paymentIntent;
    }

    public function handleWebhook(Request $request): JsonResponse
    {
        return new JsonResponse(null, 200);
    }

    public function getDriver(): string
    {
        return 'test';
    }

    public function getType(): string
    {
        return 'test';
    }
}
