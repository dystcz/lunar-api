<?php

namespace Dystcz\LunarApi\Domain\Carts\Models;

use Dystcz\LunarApi\Domain\Carts\Actions\SetPaymentOption;
use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\ValueObjects\PaymentBreakdown;
use Dystcz\LunarApi\Domain\PaymentOptions\Data\PaymentOption;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\DataTypes\Price;
use Lunar\Models\Cart as LunarCart;

/**
 * @property PaymentOption|null $paymentOption
 * @property Price|null $paymentSubTotal
 * @property Price|null $paymentTotal
 * @property PaymentOption|null $paymentOptionOverride
 * @property array $paymentEstimateMeta
 * @property PaymentBreakdown|null $paymentBreakdown
 */
class Cart extends LunarCart
{
    use HashesRouteKey;

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->cachableProperties = array_merge($this->cachableProperties, [
            'paymentOption',
            'paymentSubTotal',
            'paymentTotal',
        ]);
    }

    /**
     * The applied payment option.
     */
    public ?PaymentOption $paymentOption = null;

    /**
     * The payment sub total for the cart.
     */
    public ?Price $paymentSubTotal = null;

    /**
     * The payment total for the cart.
     */
    public ?Price $paymentTotal = null;

    /**
     * Additional payment estimate meta data.
     */
    public array $paymentEstimateMeta = [];

    /**
     * All the payment breakdowns for the cart.
     */
    public ?PaymentBreakdown $paymentBreakdown = null;

    /**
     * Set the payment option to the shipping address.
     */
    public function setPaymentOption(PaymentOption $option, bool $refresh = true): Cart
    {
        foreach (Config::get('lunar.cart.validators.set_payment_option', []) as $action) {
            App::make($action)->using(cart: $this, paymentOption: $option)->validate();
        }

        return App::make(Config::get('lunar.cart.actions.set_payment_option', SetPaymentOption::class))
            ->execute($this, $option)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Get the payment option for the cart
     */
    public function getPaymentOption(): ?PaymentOption
    {
        return PaymentManifest::getPaymentOption($this);
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
