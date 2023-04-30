<?php

/*
 * Lunar API configuration
 */

use Dystcz\LunarApi\Domain\Addresses\Http\Routing\AddressRouteGroup;
use Dystcz\LunarApi\Domain\Carts\Http\Routing\CartAddressRouteGroup;
use Dystcz\LunarApi\Domain\Carts\Http\Routing\CartLineRouteGroup;
use Dystcz\LunarApi\Domain\Carts\Http\Routing\CartRouteGroup;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use Dystcz\LunarApi\Domain\Countries\Http\Routing\CountryRouteGroup;
use Dystcz\LunarApi\Domain\Currencies\Http\Routing\CurrencyRouteGroup;
use Dystcz\LunarApi\Domain\Customers\Http\Routing\CustomerRouteGroup;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Orders\Http\Routing\OrderRouteGroup;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\Orders\Models\OrderLine;
use Dystcz\LunarApi\Domain\Shipping\Http\Routing\ShippingOptionRouteGroup;
use Dystcz\LunarApi\Domain\Tags\Http\Routing\TagRouteGroup;

return [

    // Prefix for all the API routes
    // Leave empty if you don't want to use a prefix
    'route_prefix' => 'api',

    // Middleware for all the API routes
    'route_middleware' => ['api'],

    // Additional Lunar API compatible servers
    'additional_servers' => [
        //
    ],

    'auth' => [
        'actions' => [
            'create_user_from_cart' => Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart::class,
            'register_user' => Dystcz\LunarApi\Domain\Users\Actions\RegisterUser::class,
        ],

        'notifications' => [
            'reset_password' => Illuminate\Auth\Notifications\ResetPassword::class,
            'verify_email' => Illuminate\Auth\Notifications\VerifyEmail::class,
        ],
    ],

    // Configuration for specific domains
    'domains' => [
        'associations' => [
            'route_groups' => [
                //
            ],

        ],

        'brands' => [
            'route_groups' => [
                'brands' => Dystcz\LunarApi\Domain\Brands\Http\Routing\BrandRouteGroup::class,
            ],

        ],

        'collections' => [
            'route_groups' => [
                'collections' => Dystcz\LunarApi\Domain\Collections\Http\Routing\CollectionRouteGroup::class,
            ],

        ],

        'prices' => [
            'model' => Dystcz\LunarApi\Domain\Prices\Models\Price::class,

            'route_groups' => [
                //
            ],

        ],

        'countries' => [
            'route_groups' => [
                'countries' => CountryRouteGroup::class,
            ],

        ],

        'currencies' => [
            'route_groups' => [
                'countries' => CurrencyRouteGroup::class,
            ],

        ],

        'products' => [
            'model' => Dystcz\LunarApi\Domain\Products\Models\Product::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'products' => Dystcz\LunarApi\Domain\Products\Http\Routing\ProductRouteGroup::class,
            ],

            'filters' => Dystcz\LunarApi\Domain\Products\JsonApi\Filters\ProductFilterCollection::class,

        ],

        'variants' => [
            'model' => Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant::class,

            'route_groups' => [
                //
            ],

        ],

        'carts' => [
            'model' => Cart::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'carts' => CartRouteGroup::class,
            ],

        ],

        'cart_lines' => [
            'model' => CartLine::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'cart_lines' => CartLineRouteGroup::class,
            ],

        ],

        'cart_addresses' => [
            'route_groups' => [
                'cart_addresses' => CartAddressRouteGroup::class,
            ],

        ],

        'customers' => [
            'model' => Customer::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'customers' => CustomerRouteGroup::class,
            ],

        ],

        'addresses' => [
            'route_groups' => [
                'addresses' => AddressRouteGroup::class,
            ],

        ],

        'orders' => [
            'model' => Order::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'orders' => OrderRouteGroup::class,
            ],

        ],

        'order_lines' => [
            'model' => OrderLine::class,

        ],

        'shipping_options' => [
            'route_groups' => [
                'shipping_options' => ShippingOptionRouteGroup::class,
            ],

        ],

        'urls' => [
            'route_groups' => [
                'urls' => Dystcz\LunarApi\Domain\Urls\Http\Routing\UrlRouteGroup::class,
            ],

        ],

        'tags' => [
            'route_groups' => [
                'tags' => TagRouteGroup::class,
            ],
        ],
    ],

    // Pagination defaults
    'pagination' => [
        'per_page' => 12,
        'max_size' => 25,
    ],

    'taxation' => [
        'prices_with_default_tax' => true,
    ],

];
