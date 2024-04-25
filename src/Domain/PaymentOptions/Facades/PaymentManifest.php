<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Facades;

use Closure;
use Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentManifest as PaymentManifestContract;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Lunar\Models\Cart;

/**
 * @method static PaymentManifest addOption(PaymentOption $option)
 * @method static PaymentManifest addOptions(Collection $options)
 * @method static PaymentManifest clearOptions()
 * @method static PaymentManifest getOptionUsing(Closure $closure)
 * @method static Collection getOptions(Cart $cart)
 * @method static ?PaymentOption getOption(Cart $cart, string $identifier)
 * @method static ?PaymentOption getPaymentOption(Cart $cart)
 *
 * @see \Dystcz\LunarApi\Domain\PaymentOptions\Manifests\PaymentManifest
 */
class PaymentManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return PaymentManifestContract::class;
    }
}
