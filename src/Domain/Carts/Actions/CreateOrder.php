<?php

namespace Dystcz\LunarApi\Domain\Carts\Actions;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Actions\AbstractAction;
use Lunar\Exceptions\DisallowMultipleCartOrdersException;
use Lunar\Facades\DB;
use Lunar\Facades\ModelManifest;
use Lunar\Jobs\Orders\MarkAsNewCustomer;
use Lunar\Models\Contracts\Cart as CartContract;
use Lunar\Models\Contracts\Order as OrderContract;

final class CreateOrder extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        CartContract $cart,
        bool $allowMultipleOrders = false,
        ?int $orderIdToUpdate = null
    ): self {
        $this->passThrough = DB::transaction(function () use ($cart, $allowMultipleOrders, $orderIdToUpdate) {
            /** @var Order $order */
            /** @var Cart $cart */
            $order = $cart->draftOrder($orderIdToUpdate)->first() ?: App::make(ModelManifest::get(OrderContract::class));

            if ($cart->hasCompletedOrders() && ! $allowMultipleOrders) {
                throw new DisallowMultipleCartOrdersException;
            }

            $order->fill([
                'cart_id' => $cart->id,
            ]);

            $order = app(Pipeline::class)
                ->send($order)
                ->through(
                    config('lunar.orders.pipelines.creation', [])
                )->thenReturn(function ($order) {
                    return $order;
                });

            // If we don't want to forget cart after order created, we need to mark discounts as used after order created
            if (! Config::get('lunar-api.domains.carts.settings.forget_cart_after_order_created', true)) {
                $cart->discounts?->each(
                    fn ($discount) => $discount->markAsUsed($cart)->discount->save(),
                );
            }

            $cart->save();

            MarkAsNewCustomer::dispatch($order->id);

            $order->refresh();

            return $order;
        });

        return $this;
    }
}
