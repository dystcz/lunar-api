<?php

namespace Dystcz\LunarApi\Domain\Addresses\Http\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum AddressType: string
{
    case BILLING = 'billing';
    case SHIPPING = 'shipping';
}
