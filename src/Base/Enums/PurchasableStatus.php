<?php

namespace Dystcz\LunarApi\Base\Enums;

enum PurchasableStatus: string
{
    case ALWAYS = 'always';
    case IN_STOCK = 'in_stock';
    case BACKORDER = 'backorder';
}
