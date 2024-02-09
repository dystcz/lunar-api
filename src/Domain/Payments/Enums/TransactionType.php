<?php

namespace Dystcz\LunarApi\Domain\Payments\Enums;

enum TransactionType: string
{
    case INTENT = 'intent';
    case CAPTURE = 'capture';
    case REFUND = 'refund';
}
