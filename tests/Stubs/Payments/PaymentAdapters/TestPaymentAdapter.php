<?php

namespace Dystcz\LunarApi\Tests\Stubs\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentIntent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Cart;

class TestPaymentAdapter extends PaymentAdapter
{
    public function createIntent(Cart $cart, array $meta = []): PaymentIntent
    {
        $this->cart = $cart;

        $paymentIntent = new PaymentIntent(
            id: 1,
            amount: 500,
            status: 'intent',
        );

        $this->createTransaction($paymentIntent);

        return $paymentIntent;
    }

    public function handleWebhook(Request $request): JsonResponse
    {
        return response()->json(null, 200);
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
