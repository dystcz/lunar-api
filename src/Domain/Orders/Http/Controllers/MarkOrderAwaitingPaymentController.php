<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\MarkAwaitingPayment;
use LaravelJsonApi\Core\Responses\DataResponse;

class MarkOrderAwaitingPaymentController extends Controller
{
    public function markAwaitingPayment(
        Order $order,
        MarkAwaitingPayment $markAwaitingPayment,
    ): DataResponse {
        $this->authorize('viewSigned', $order);

        $order = $markAwaitingPayment($order);

        return DataResponse::make($order)
            ->didntCreate();
    }
}
