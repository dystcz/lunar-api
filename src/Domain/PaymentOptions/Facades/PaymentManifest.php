<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Facades;

use Closure;
use Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentManifest as PaymentManifestContract;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Lunar\Models\Contracts\Cart as CartContract;

/**
 * @method static PaymentManifest addOption(PaymentOption $option)
 * @method static PaymentManifest addOptions(Collection $options)
 * @method static PaymentManifest clearOptions()
 * @method static PaymentManifest getOptionUsing(Closure $closure)
 * @method static Collection getOptions(CartContract $cart)
 * @method static ?PaymentOption getOption(CartContract $cart, string $identifier)
 * @method static ?PaymentOption getPaymentOption(CartContract $cart)
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
