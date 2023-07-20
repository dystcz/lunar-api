<?php

namespace Dystcz\LunarApi\Routing\Middleware;

use Closure;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Lunar\Facades\CartSession;

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

            $token = $this->createCartWithToken();

            $response = $next($request);

            $response = $this->setToken($response, $token);

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
     * @param  string  $token The token
     * @return Cart|null The cart or null if not found
     */
    public function findCartByToken(string $token): ?Cart
    {
        return Cart::where('meta->token', $token)->first();
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
     * @param  Request  $request The request object
     * @return string|null The token or null if not found
     */
    public function getToken($request): ?string
    {
        return $request->header('X-Cart-Token', null);
    }

    /**
     * Set the token in the response header X-Cart-Token
     *
     * @param  Response  $response The response object
     * @param $token The token
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
     * @return string The token created for the cart
     */
    public function createCartWithToken(): string
    {
        $cart = CartSession::current();

        $token = $this->generateToken();

        $meta = $cart->meta ?? [];

        $meta['token'] = $token;

        $cart->meta = $meta;

        $cart->save();

        return $token;
    }
}
