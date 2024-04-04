<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCart as CheckoutCartContract;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Events\OrderCreated;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\CartSessionInterface;
use Lunar\Models\Order as LunarOrder;

class CheckoutCart implements CheckoutCartContract
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    public function __invoke(Cart $cart): Order
    {
        /** @var LunarOrder $order */
        $order = $cart->createOrder(
            allowMultipleOrders: Config::get('lunar-api.general.checkout.multiple_orders_per_cart', false),
        );

        $model = Order::query()
            ->with([
                'cart' => fn ($query) => $query->with(Config::get('lunar.cart.eager_load', [])),
            ])
            ->where('id', $order->id)
            ->firstOrFail();

        if (Config::get('lunar-api.general.checkout.forget_cart_after_order_creation', true)) {
            $this->cartSession->forget();
        }

        OrderCreated::dispatch($model);

        return $model;
    }
}
