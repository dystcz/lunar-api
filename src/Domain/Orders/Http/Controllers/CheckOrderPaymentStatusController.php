<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\CheckOrderPaymentStatusRequest;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Payments\Actions\CheckOrderPaymentStatus;
use LaravelJsonApi\Core\Responses\DataResponse;

class CheckOrderPaymentStatusController extends Controller
{
    public function checkOrderPaymentStatus(
        // CheckOrderPaymentStatusRequest $request,
        Order $order,
        CheckOrderPaymentStatus $checkPaymentStatus,
    ): DataResponse {
        $this->authorize('viewSigned', $order);

        $transaction = $checkPaymentStatus($order);

        return DataResponse::make([]);
    }
}
