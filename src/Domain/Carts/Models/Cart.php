<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Carts\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Carts\Contracts\Cart as CartContract;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown;
use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart as LunarCart;
use Lunar\Models\Contracts\Cart as LunarCartContract;

/**
 * @property PaymentOption|null $paymentOption
 * @property Price|null $paymentSubTotal
 * @property Price|null $paymentTaxTotal
 * @property Price|null $paymentTotal
 * @property PaymentOption|null $paymentOptionOverride
 * @property array $paymentEstimateMeta
 * @property PaymentBreakdown|null $paymentBreakdown
 * @property TaxBreakdown|null $paymentTaxBreakdown
 *
 * @method Cart setPaymentOption(PaymentOption $option, bool $refresh = true)
 * @method Cart unsetPaymentOption(PaymentOption $option, bool $refresh = true)
 * @method ?PaymentOption getPaymentOption()
 */
#[ReplaceModel(LunarCartContract::class)]
class Cart extends LunarCart implements CartContract
{
    use InteractsWithLunarApi;
}
