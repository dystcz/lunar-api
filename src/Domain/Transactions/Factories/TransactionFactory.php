<?php

namespace Dystcz\LunarApi\Domain\Transactions\Factories;

use Dystcz\LunarApi\Domain\Transactions\Models\Transaction;
use Lunar\Database\Factories\TransactionFactory as LunarTransactionFactory;

class TransactionFactory extends LunarTransactionFactory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
