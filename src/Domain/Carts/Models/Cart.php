<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Contracts\Cart as CartContract;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Traits\InteractsWithPaymentOptions;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Base\ValueObjects\Cart\TaxBreakdown;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart as LunarCart;

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
class Cart extends LunarCart implements CartContract, CurrentSessionCart
{
    use HashesRouteKey;
    use InteractsWithPaymentOptions;

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->cachableProperties = array_merge($this->cachableProperties, [
            'paymentOption',
            'paymentSubTotal',
            'paymentTaxTotal',
            'paymentTotal',
            'paymentTaxBreakdown',
        ]);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CartCreated::class,
    ];
}
