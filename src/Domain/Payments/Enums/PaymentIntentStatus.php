<?php

namespace Dystcz\LunarApi\Domain\Payments\Enums;

enum PaymentIntentStatus: string
{
    case INTENT = 'intent';
    case SUCCEEDED = 'succeeded';
}
