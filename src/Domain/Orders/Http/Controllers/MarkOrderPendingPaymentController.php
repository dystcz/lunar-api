<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\MarkPendingPayment;
use LaravelJsonApi\Core\Responses\DataResponse;

class MarkOrderPendingPaymentController extends Controller
{
    public function markPendingPayment(
        Order $order,
        MarkPendingPayment $markPendingPayment,
    ): DataResponse {
        $this->authorize('viewSigned', $order);

        $order = $markPendingPayment($order);

        return DataResponse::make($order)
            ->didntCreate();
    }
}
