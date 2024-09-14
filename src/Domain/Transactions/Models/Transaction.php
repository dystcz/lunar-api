<?php

namespace Dystcz\LunarApi\Domain\Transactions\Models;

use Dystcz\LunarApi\Domain\Transactions\Contracts\Transaction as TransactionContract;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Transaction as LunarTransaction;

class Transaction extends LunarTransaction implements TransactionContract
{
    use HashesRouteKey;
}
