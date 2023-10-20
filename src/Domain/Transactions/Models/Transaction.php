<?php

namespace Dystcz\LunarApi\Domain\Transactions\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Transaction as LunarTransaction;

class Transaction extends LunarTransaction
{
    use HashesRouteKey;
}
