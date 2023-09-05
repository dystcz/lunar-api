<?php

namespace Dystcz\LunarApi\Domain\Carts\Listeners;

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;

class CreateCartAddresses
{
    public function __construct()
    {
    }

    public function handle(CartCreated $event): void
    {
        $cart = $event->cart;

        $cart->setShippingAddress([
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

        $cart->setBillingAddress([
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
