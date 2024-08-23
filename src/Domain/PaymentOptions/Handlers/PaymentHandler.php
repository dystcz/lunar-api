<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Handlers;

use Lunar\Models\Cart;

abstract class PaymentHandler
{
    public function handle(Cart $cart): void {}
}
