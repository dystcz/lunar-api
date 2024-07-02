<?php

namespace Dystcz\LunarApi\Domain\Payments\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum PaymentIntentStatus: string
{
    case INTENT = 'intent';
    case SUCCEEDED = 'succeeded';
}
