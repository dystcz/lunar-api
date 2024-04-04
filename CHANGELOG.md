# Changelog

## 0.8.8

### Changes

- Carts do not get automatically created when fetching them unless configured with `lunar.cart.auto_create = true`. However, they are created on demand by adding a first `CartLine` to a `Cart`.
- Added custom `CartSessionAuthListener` which merges current cart in the session with previously associated user cart and returns the updated user cart.
- Added `CreateEmptyCartAddresses` action from a listener with the same name.

### ⚠️ Breaking changes

- Empty `CartAddress`es are not created automatically with `Cart` anymore. You will have to create them manually by calling the endpoint below or using your own listener for the `CartCreated` event.

#### New endpoints

| Description | Related Model / Entity | Endpoint | Method |
| ----------- | ------------- | -------- | -------|
| Create empty cart addresses | `Cart` | `/carts/-actions/create-empty-addresses` | `post` |

## 0.8.7

### ⚠️ Breaking changes

#### Endpoints

| Description | Related Model | Endpoint change | Method change |
| ----------- | ------------- | --------------- | --------------|
| Set coupon action | `Cart` | `/apply-coupon` → `/set-coupon` | `patch` → `post` |
| Unset coupon action | `Cart` | `/remove-coupon` → `/unset-coupon` | `patch` → `post` |
| Set shipping option | `CartAddress` | `/attach-shipping-option` → `/set-shipping-option` | --- |
| Unset shipping option | `CartAddress` | `/detach-shipping-option` → `/unset-shipping-option` | `delete` → `patch` |

### Purchasable payment options 🆕

In the same fashion as shipping options, purchasable payment options are now available.

#### Super quick guide

1. Create a custom `PaymentModifier`
2. Add `PaymentOption`s in the modifier handle method by calling `PaymentManifest@addOption`
3. Register the modifier in a service provider like so: `App::get(PaymentModifiers::class)->add(PaymentModifier::class);`
4. You should now see your payment options when calling the `/payment-options` endpoint

#### Endpoints for setting and unsetting payment options

| Description | Related Model / Entity | Endpoint | Method |
| ----------- | ------------- | -------- | -------|
| List available payment options | `PaymentOption` | `/payment-options` | `get` |
| Set payment option | `Cart` | `/carts/-actions/set-payment-option` | `post` |
| Unset payment option | `Cart` | `/carts/-actions/unset-payment-option` | `post` |

## 0.8.3

* Find order redundancy by @theimerj in [https://github.com/dystcz/lunar-api/pull/91](https://github.com/dystcz/lunar-api/pull/91).
Added more actions which can find order by payment intent id.
This increases the success rate of identifying the order
connected with the payment intent.
Especially useful when data integrity is not ideal.
