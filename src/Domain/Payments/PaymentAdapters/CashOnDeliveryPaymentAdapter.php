<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

class CashOnDeliveryPaymentAdapter extends OfflinePaymentAdapter
{
    /**
     * Get payment driver on which this adapter binds.
     *
     * Drivers for lunar are set in lunar.payments.types.
     * When offline is set as a driver, this adapter will be used.
     */
    public function getDriver(): string
    {
        return 'offline';
    }

    /**
     * Get payment type.
     *
     * This key serves is an identification for this adapter.
     * That means that offline driver is handled by this adapter if configured.
     */
    public function getType(): string
    {
        return 'cash-on-delivery';
    }
}
