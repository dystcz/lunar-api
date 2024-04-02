# Changelog

## 0.8.7

### ⚠️ Breaking changes

#### Endpoints

| Description | Related Model | Endpoint change | Method change |
| ----------- | ------------- | --------------- | --------------|
| Set coupon action | `Cart` | `/apply-coupon` → `/set-coupon` | `patch` → `post` |
| Unset coupon action | `Cart` | `/remove-coupon` → `/unset-coupon` | `patch` → `post` |
| Set shipping option | `CartAddress` | `/attach-shipping-option` → `/set-shipping-option` | --- |
| Unset shipping option | `CartAddress` | `/detach-shipping-option` → `/unset-shipping-option` | `delete` → `patch` |

### New endpoints

| Description | Related Model | Endpoint | Method |
| ----------- | ------------- | -------- | -------|
| Set payment option | `Cart` | `/carts/-actions/set-payment-option` | `post` |
| Unset payment option | `Cart` | `/carts/-actions/unset-payment-option` | `post` |

## 0.8.3

* Find order redundancy by @theimerj in [https://github.com/dystcz/lunar-api/pull/91](https://github.com/dystcz/lunar-api/pull/91).
Added more actions which can find order by payment intent id.
This increases the success rate of identifying the order
connected with the payment intent.
Especially useful when data integrity is not ideal.
