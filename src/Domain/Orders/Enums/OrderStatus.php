<?php

namespace Dystcz\LunarApi\Domain\Orders\Enums;

enum OrderStatus: string
{
    case AWAITING_PAYMENT = 'awaiting-payment';
    case PENDING_PAYMENT = 'pending-payment';
    case BANK_PAYMENT_RECEIVED = 'bank-payment-received';
    case PAYMENT_RECEIVED = 'payment-received';
    case MANUFACTURING = 'manufacturing';
    case DISPATCHED = 'dispatched';
    case DELIVERED = 'delivered';
    case ON_HOLD = 'on-hold';
}
