<?php

namespace Dystcz\LunarApi\Tests\Stubs\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Payments\Contracts\PaymentIntent as PaymentIntentContract;
use Dystcz\LunarApi\Domain\Payments\Data\PaymentIntent;
use Dystcz\LunarApi\Domain\Payments\Enums\PaymentIntentStatus;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Cart;

class TestPaymentAdapter extends PaymentAdapter
{
    public function createIntent(Cart $cart, array $meta = []): PaymentIntentContract
    {
        $intent = [
            'id' => 1,
            'amount' => 500,
            'status' => PaymentIntentStatus::INTENT->value,
        ];

        $paymentIntent = new PaymentIntent((object) $intent);

        $this->createIntentTransaction($cart, $paymentIntent);

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
