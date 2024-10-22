<?php

namespace Dystcz\LunarApi\Domain\Transactions\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Transactions\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Transactions\Contracts\Transaction as TransactionContract;
use Lunar\Models\Contracts\Transaction as LunarTransactionContract;
use Lunar\Models\Transaction as LunarTransaction;

#[ReplaceModel(LunarTransactionContract::class)]
class Transaction extends LunarTransaction implements TransactionContract
{
    use InteractsWithLunarApi;
}
