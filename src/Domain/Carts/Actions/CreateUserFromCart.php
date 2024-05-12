<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressRequest;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Contracts\CreatesUserFromCart;
use Dystcz\LunarApi\Domain\Users\Contracts\RegistersUser;
use Dystcz\LunarApi\Domain\Users\Data\UserData;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
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

        $customer->addresses()->createMany($this->getDataForCustomerAddresses($cart));

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
    protected function getDataForCustomerAddresses(Cart $cart): array
    {
        $shippingAddress = $cart->shippingAddress;
        $billingAddress = $cart->billingAddress;

        $addresses = [
            'shipping' => $this->getAddressData($shippingAddress, $billingAddress),
            'billing' => $this->getAddressData($billingAddress, $shippingAddress),
        ];

        return array_filter($addresses);
    }

    /**
     * Get address data in array.
     */
    protected function getAddressData(?CartAddress $primary, ?CartAddress $secondary): ?array
    {
        if (! $primary) {
            return null;
        }

        $data = [
            'country_id' => $primary->country_id ?? $secondary->country_id,
            'title' => $primary->title ?? $secondary->title,
            'first_name' => $primary->first_name ?? $secondary->first_name,
            'last_name' => $primary->last_name ?? $secondary->last_name,
            'company_name' => $primary->company_name ?? $secondary->company_name,
            'line_one' => $primary->line_one ?? $secondary->line_one,
            'line_two' => $primary->line_two ?? $secondary->line_two,
            'line_three' => $primary->line_three ?? $secondary->line_three,
            'city' => $primary->city ?? $secondary->city,
            'state' => $primary->state ?? $secondary->state,
            'postcode' => $primary->postcode ?? $secondary->postcode,
            'delivery_instructions' => $primary->delivery_instructions ?? $secondary->delivery_instructions,
            'contact_email' => $primary->contact_email ?? $secondary->contact_email,
            'contact_phone' => $primary->contact_phone ?? $secondary->contact_phone,
            'meta' => (array) ($primary->meta ?? $secondary->meta),
            'shipping_default' => $primary->type === 'shipping',
            'billing_default' => $primary->type === 'billing',
        ];

        /** @var AddressRequest $addressRequest */
        $addressRequest = App::make(AddressRequest::class);

        $validator = Validator::make($data, $addressRequest->modelRules());

        return $validator->passes() ? $data : null;
    }
}
