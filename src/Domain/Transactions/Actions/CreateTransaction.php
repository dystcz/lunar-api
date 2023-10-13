<?php

namespace Dystcz\LunarApi\Domain\Transactions\Actions;

use Dystcz\LunarApi\Domain\Transactions\Data\TransactionData;
use Dystcz\LunarApi\Domain\Transactions\Models\Transaction;

class CreateTransaction
{
    /**
     * Create or update transaction.
     */
    public function __invoke(TransactionData $transactionData): Transaction
    {
        return Transaction::updateOrCreate(
            ['reference' => $transactionData->reference, 'order_id' => $transactionData->order_id],
            $transactionData->toArray(),
        );
    }
}
