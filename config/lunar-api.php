<?php

/*
 * Lunar API configuration
 */
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
                'countries' => Dystcz\LunarApi\Domain\Countries\Http\Routing\CountryRouteGroup::class,
            ],

        ],

        'currencies' => [
            'route_groups' => [
                'countries' => Dystcz\LunarApi\Domain\Currencies\Http\Routing\CurrencyRouteGroup::class,
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
            'model' => Dystcz\LunarApi\Domain\Carts\Models\Cart::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'carts' => Dystcz\LunarApi\Domain\Carts\Http\Routing\CartRouteGroup::class,
            ],

        ],

        'cart_lines' => [
            'model' => Dystcz\LunarApi\Domain\Carts\Models\CartLine::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'cart_lines' => Dystcz\LunarApi\Domain\Carts\Http\Routing\CartLineRouteGroup::class,
            ],

        ],

        'cart_addresses' => [
            'route_groups' => [
                'cart_addresses' => Dystcz\LunarApi\Domain\Carts\Http\Routing\CartAddressRouteGroup::class,
            ],

        ],

        'customers' => [
            'model' => Dystcz\LunarApi\Domain\Customers\Models\Customer::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'customers' => Dystcz\LunarApi\Domain\Customers\Http\Routing\CustomerRouteGroup::class,
            ],

        ],

        'addresses' => [
            'route_groups' => [
                'addresses' => Dystcz\LunarApi\Domain\Addresses\Http\Routing\AddressRouteGroup::class,
            ],

        ],

        'orders' => [
            'model' => Dystcz\LunarApi\Domain\Orders\Models\Order::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'orders' => Dystcz\LunarApi\Domain\Orders\Http\Routing\OrderRouteGroup::class,
            ],

        ],

        'order_lines' => [
            'model' => Dystcz\LunarApi\Domain\Orders\Models\OrderLine::class,

        ],

        'shipping_options' => [
            'route_groups' => [
                'shipping_options' => Dystcz\LunarApi\Domain\Shipping\Http\Routing\ShippingOptionRouteGroup::class,
            ],

        ],

        'urls' => [
            'route_groups' => [
                'urls' => Dystcz\LunarApi\Domain\Urls\Http\Routing\UrlRouteGroup::class,
            ],

        ],

        'tags' => [
            'route_groups' => [
                'tags' => Dystcz\LunarApi\Domain\Tags\Http\Routing\TagRouteGroup::class,
            ],
        ],

        'media' => [
            'route_groups' => [
                'media' => Dystcz\LunarApi\Domain\Media\Http\Routing\MediaRouteGroup::class,
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
