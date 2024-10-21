<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Orders\Contracts\CheckOrderPaymentStatusController as CheckOrderPaymentStatusControllerContract;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\CheckOrderPaymentStatusQuery;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Contracts\Order as OrderContract;

class CheckOrderPaymentStatusController extends Controller implements CheckOrderPaymentStatusControllerContract
{
    public function checkOrderPaymentStatus(
        OrderSchema $schema,
        CheckOrderPaymentStatusQuery $query,
        OrderContract $order,
    ): DataResponse {
        /** @var Order $order */
        $this->authorize('viewSigned', $order);

        $order->load([
            'latestTransaction',
        ]);

        return DataResponse::make($order)
            ->withIncludePaths([
                'latest_transaction',
            ])
            ->withSparseFieldSets([
                'orders' => [
                    // 'latest_transaction',
                    'status',
                    'placed_at',
                ],
                'transactions' => [
                    'type',
                    'driver',
                    'status',
                    'success',
                    'error',
                ],
            ])
            ->withMeta([
                'is_placed' => $order->isPlaced(),
                'cancelled' => in_array($order->latestTransaction?->status, ['cancelled']),
                'failed' => in_array($order->latestTransaction?->status, ['failed']),
            ])
            ->didntCreate();
    }
}
