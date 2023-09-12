<?php

namespace Dystcz\LunarApi\Tests\Stubs\Payments\PaymentAdapters;

use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdapter;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentIntent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Cart;

class TestPaymentAdapter extends PaymentAdapter
{
    public function createIntent(Cart $cart): PaymentIntent
    {
        return new PaymentIntent(1);
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
