<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Data\UserData;
use Illuminate\Foundation\Auth\User as Authenticatable;
use RuntimeException;

class CreateUserFromCart implements CreatesUserFromCart
{
    public function __construct(
        protected RegistersUser $registerUser,
    ) {
    }

    /**
     * Create a user from a cart.
     */
    public function __invoke(
        Cart $cart,
    ): ?Authenticatable {
        if ($cart->user_id) {
            return $cart->user;
        }

        $shippingAddress = $cart->shippingAddress;

        if (! $shippingAddress) {
            throw new RuntimeException('Cart has no shipping address');
        }

        if (! $shippingAddress->contact_email) {
            throw new RuntimeException('Cart has no shipping address email');
        }

        /** @var Authenticatable $user */
        $user = $this->registerUser->register(
            new UserData(
                name: implode(' ', [$shippingAddress->first_name, $shippingAddress->last_name]),
                email: $shippingAddress->contact_email,
            ),
        );

        $customer = $this->getCustomer($user, $shippingAddress);

        $customer->addresses()->createMany($this->getAddresses($cart));

        $cart->update(['user_id' => $user->id]);

        return $user;
    }

    /**
     * Get or create a customer.
     */
    protected function getCustomer(Authenticatable $user, CartAddress $shippingAddress): Customer
    {
        /** @var Customer $customer */
        $customer = $user->customers()->first();

        if (! $customer) {
            return $user->customers()->create([
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
            ]);
        }

        $customer->first_name = $customer->first_name ?: $shippingAddress->first_name;
        $customer->last_name = $customer->last_name ?: $shippingAddress->last_name;

        $customer->save();

        return $customer;
    }

    /**
     * Get addresses from cart.
     */
    protected function getAddresses(Cart $cart): array
    {
        $shippingAddress = $cart->shippingAddress;
        $billingAddress = $cart->billingAddress;

        $addresses = [
            $this->getAddress($shippingAddress),
            $this->getAddress($billingAddress),
        ];

        return array_filter($addresses);
    }

    /**
     * Get address data in array.
     */
    protected function getAddress(?CartAddress $address): ?array
    {
        return ! $address ? null : [
            'country_id' => $address->country_id,
            'title' => $address->title,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'company_name' => $address->company_name,
            'line_one' => $address->line_one,
            'line_two' => $address->line_two,
            'line_three' => $address->line_three,
            'city' => $address->city,
            'state' => $address->state,
            'postcode' => $address->postcode,
            'delivery_instructions' => $address->delivery_instructions,
            'contact_email' => $address->contact_email,
            'contact_phone' => $address->contact_phone,
            'meta' => (array) $address->meta,
            'shipping_default' => $address->type === 'shipping',
            'billing_default' => $address->type === 'billing',
        ];
    }
}
