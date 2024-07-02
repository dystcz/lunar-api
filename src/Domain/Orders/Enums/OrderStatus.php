<?php

namespace Dystcz\LunarApi\Domain\Orders\Enums;

use Dystcz\LunarApi\Domain\Orders\Contracts\OrderStatusContract;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum OrderStatus: string implements OrderStatusContract
{
    case AWAITING_PAYMENT = 'awaiting-payment';
    case PENDING_PAYMENT = 'pending-payment';
    case PAYMENT_RECEIVED = 'payment-received';
    case MANUFACTURING = 'manufacturing';
    case DISPATCHED = 'dispatched';
    case DELIVERED = 'delivered';
    case ON_HOLD = 'on-hold';
}
