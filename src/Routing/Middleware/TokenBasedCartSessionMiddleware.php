<?php

namespace Dystcz\LunarApi\Routing\Middleware;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Lunar\Facades\CartSession;
use Lunar\Models\Order;

class TokenBasedCartSessionMiddleware
{
    /**
     * List of routes which should be handled by this middleware
     */
    public function getRoutes(): array
    {
        return [
            'v1.carts.myCart',
            'v1.carts.applyCoupon',
            'v1.carts.checkout',
            'v1.carts.clear',
            'v1.cart-lines.store',
            'v1.cart-lines.update',
            'v1.cart-lines.destroy',
            'v1.shipping-options.index',
            'v1.cart-addresses.store',
            'v1.cart-addresses.update',
            'v1.cart-addresses.attachShippingOption',
            'v1.cart-addresses.detachShippingOption',
            'v1.cart-addresses.updateCountry',
        ];
    }

    public function handle($request, Closure $next)
    {
        /**
         * Early return if the route is not in the list
         */
        if (! in_array($request->route()->getName(), $this->getRoutes())) {
            return $next($request);
        }

        $token = $this->getToken($request);

        /**
         * If the token is not present, we create a new cart and return the token in the response header X-Cart-Token
         */
        if (! $token) {

            $cart = $this->createCartWithToken();

            $response = $next($request);

            $response = $this->setToken($response, $cart->meta->token);

            return $response;
        }

        /**
         * If the token is present, we try to find the cart by the token
         */
        $cart = $this->findCartByToken($token);

        /**
         * If the cart is not found, we create a new cart and return the token in the response header X-Cart-Token
         */
        if (! $cart === null) {

            $response = $next($request);
            $response->header('X-Cart-Token', $cart->meta->token);

            return $next($request);
        }

        /**
         * If the cart is found, we use it as the current cart
         */
        CartSession::use($cart);

        /**
         * We return the response
         */
        return $next($request);
    }

    /**
     * Find a cart by the token
     *
     * @param  string  $token  The token
     * @return Cart|null The cart or null if not found
     */
    public function findCartByToken(string $token): ?Cart
    {
        /**
         * @var Cart $cart
         */
        $cart = Cart::where('meta->token', $token)->first();

        if ($cart === null) {
            return $this->createCartWithToken();
        }

        if ($order = $cart->orders()->first()) {

            /**
             * @var Order $order
             */
            $orderIsPlaced = $order->isPlaced();

            if ($orderIsPlaced) {
                return $this->createCartWithToken();
            }
        }

        return $cart;
    }

    /**
     * Generate a token
     *
     * @return string The token
     */
    public function generateToken(): string
    {
        return Str::random(40);
    }

    /**
     * Get the token from the request header X-Cart-Token
     *
     * @param  Request  $request  The request object
     * @return string|null The token or null if not found
     */
    public function getToken($request): ?string
    {
        return $request->header('X-Cart-Token', null);
    }

    /**
     * Set the token in the response header X-Cart-Token
     *
     * @param  Response  $response  The response object
     * @param  $token  The token
     * @return Response The response object
     */
    public function setToken(Response $response, $token): Response
    {
        $response->headers->set('X-Cart-Token', $token);

        return $response;
    }

    /**
     * Create a new cart and return the token
     *
     * @return Cart The cart
     */
    public function createCartWithToken(): Cart
    {
        $cart = CartSession::current();

        $token = $this->generateToken();

        $meta = (object) $cart->meta;

        $meta->token = $token;

        $cart->meta = $meta;

        $cart->save();

        return $cart;
    }
}
