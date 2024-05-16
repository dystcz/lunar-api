<?php

namespace Dystcz\LunarApi\Base\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum PurchasableStatus: string
{
    case ALWAYS = 'always';
    case IN_STOCK = 'in_stock';
    case BACKORDER = 'backorder';
}
