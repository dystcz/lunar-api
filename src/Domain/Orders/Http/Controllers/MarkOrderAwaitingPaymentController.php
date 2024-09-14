<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Orders\Contracts\MarkOrderAwaitingPaymentController as MarkOrderAwaitingPaymentControllerContract;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\MarkAwaitingPayment;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Contracts\Order as OrderContract;

class MarkOrderAwaitingPaymentController extends Controller implements MarkOrderAwaitingPaymentControllerContract
{
    public function markAwaitingPayment(
        OrderContract $order,
        MarkAwaitingPayment $markAwaitingPayment,
    ): DataResponse {
        /** @var Order $order */
        $this->authorize('viewSigned', $order);

        $order = $markAwaitingPayment($order);

        return DataResponse::make($order)
            ->didntCreate();
    }
}
