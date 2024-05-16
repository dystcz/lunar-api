<?php

namespace Dystcz\LunarApi\Domain\Payments\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum TransactionType: string
{
    case INTENT = 'intent';
    case CAPTURE = 'capture';
    case REFUND = 'refund';
}
