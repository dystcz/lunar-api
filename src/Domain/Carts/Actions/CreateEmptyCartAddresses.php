<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Support\Actions\Action;
use Lunar\Models\Contracts\Cart as CartContract;

class CreateEmptyCartAddresses extends Action
{
    public function __construct() {}

    /**
     * Create empty addresses for a cart.
     */
    public function handle(CartContract $cart): CartContract
    {
        /** @var Cart $cart */
        $cart = $this->createShippingAddress($cart);
        $cart = $this->createBillingAddress($cart);

        return $cart;
    }

    public function createShippingAddress(CartContract $cart): CartContract
    {
        /** @var Cart $cart */
        return $cart->setShippingAddress([
            'country_id' => null,
            'title' => null,
            'first_name' => null,
            'last_name' => null,
            'company_name' => null,
            'line_one' => null,
            'line_two' => null,
            'line_three' => null,
            'city' => null,
            'state' => null,
            'postcode' => null,
            'delivery_instructions' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'meta' => null,
        ]);
    }

    public function createBillingAddress(CartContract $cart): CartContract
    {
        /** @var Cart $cart */
        return $cart->setBillingAddress([
            'country_id' => null,
            'title' => null,
            'first_name' => null,
            'last_name' => null,
            'company_name' => null,
            'line_one' => null,
            'line_two' => null,
            'line_three' => null,
            'city' => null,
            'state' => null,
            'postcode' => null,
            'delivery_instructions' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'meta' => null,
        ]);
    }
}
