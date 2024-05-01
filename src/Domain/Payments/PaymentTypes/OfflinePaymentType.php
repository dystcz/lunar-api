<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentTypes;

use Carbon\Carbon;
use Dystcz\LunarApi\Domain\Payments\Actions\GetLastOrderTransaction;
use Dystcz\LunarApi\Domain\Payments\Enums\PaymentIntentStatus;
use Dystcz\LunarApi\Domain\Payments\Enums\TransactionType;
use Dystcz\LunarApi\Domain\Payments\PaymentAdapters\PaymentAdaptersRegister;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\Models\Transaction;
use Lunar\PaymentTypes\AbstractPayment;

class OfflinePaymentType extends AbstractPayment
{
    public function __construct(
        protected PaymentAdaptersRegister $register
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function authorize(string $paymentType = 'offline'): PaymentAuthorize
    {
        $meta = array_merge(
            (array) $this->order->meta,
            $this->data['meta'] ?? []
        );

        $status = $this->data['authorized'] ?? null;

        $this->order->update([
            'status' => $status ?? $this->config['authorized'] ?? 'payment-received',
            'meta' => $meta,
            'placed_at' => Carbon::now(),
        ]);

        $this->createCaptureTransaction($paymentType);

        return new PaymentAuthorize(true);
    }

    /**
     * Create transaction for the payment.
     */
    protected function createCaptureTransaction(string $paymentType): Transaction
    {
        $paymentAdapter = $this->register->get($paymentType);

        $lastTransaction = (new GetLastOrderTransaction)(
            order: $this->order,
            driver: $paymentAdapter->getDriver(),
        );

        $transaction = $paymentAdapter->createTransaction(
            model: $this->order,
            type: TransactionType::CAPTURE,
            reference: "{$paymentType}-{$this->order->reference}",
            status: PaymentIntentStatus::SUCCEEDED->value,
            success: true,
            amount: $this->order->total->value,
            parentId: $lastTransaction->id,
            meta: $this->data['meta'] ?? [],
        );

        return $transaction;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {
        return new PaymentRefund(true);
    }

    /**
     * {@inheritDoc}
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {
        return new PaymentCapture(true);
    }
}
