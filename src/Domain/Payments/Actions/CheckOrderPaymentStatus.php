<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarApi\Domain\Transactions\Models\Transaction;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Order;

class CheckOrderPaymentStatus
{
    public function __construct(
    ) {
    }

    public function __invoke(Order $order): DataResponse
    {
        $latestTransaction = Transaction::query()
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return DataResponse::make($latestTransaction)
            ->withMeta([
                //
            ]);
    }
}
