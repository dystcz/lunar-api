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

    // Configuration for schemas
    'schemas' => [
        'use_hashids' => env('LUNAR_API_USE_HASHIDS', false),
    ],

    // Configuration for specific domains
    'domains' => [
        'addresses' => [
            'schema' => Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Addresses\Http\Routing\AddressRouteGroup::class,
            ],
        ],

        'associations' => [
            'schema' => Dystcz\LunarApi\Domain\ProductAssociations\JsonApi\V1\ProductAssociationSchema::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [],
        ],

        'brands' => [
            'schema' => Dystcz\LunarApi\Domain\Brands\JsonApi\V1\BrandSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Brands\Http\Routing\BrandRouteGroup::class,
            ],
        ],

        'cart_addresses' => [
            'schema' => Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\CartAddresses\Http\Routing\CartAddressRouteGroup::class,
            ],
        ],

        'cart_lines' => [
            'schema' => Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\CartLines\Http\Routing\CartLineRouteGroup::class,
            ],
        ],

        'carts' => [
            'schema' => Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Carts\Http\Routing\CartRouteGroup::class,
            ],
            'forget_cart_after_order_created' => true,
        ],

        'channels' => [
            'schema' => Dystcz\LunarApi\Domain\Channels\JsonApi\V1\ChannelSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Channels\Http\Routing\ChannelRouteGroup::class,
            ],
        ],

        'collections' => [
            'schema' => Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Collections\Http\Routing\CollectionRouteGroup::class,
            ],
        ],

        'countries' => [
            'schema' => Dystcz\LunarApi\Domain\Countries\JsonApi\V1\CountrySchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Countries\Http\Routing\CountryRouteGroup::class,
            ],
        ],

        'currencies' => [
            'schema' => Dystcz\LunarApi\Domain\Currencies\JsonApi\V1\CurrencySchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Currencies\Http\Routing\CurrencyRouteGroup::class,
            ],
        ],

        'customers' => [
            'schema' => Dystcz\LunarApi\Domain\Customers\JsonApi\V1\CustomerSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Customers\Http\Routing\CustomerRouteGroup::class,
            ],
        ],

        'media' => [
            'schema' => Dystcz\LunarApi\Domain\Media\JsonApi\V1\MediaSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Media\Http\Routing\MediaRouteGroup::class,
            ],
            'conversions' => null,
        ],

        'orders' => [
            'schema' => Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Orders\Http\Routing\OrderRouteGroup::class,
            ],
            'sign_show_route' => true,
        ],

        'order_addresses' => [
            'schema' => Dystcz\LunarApi\Domain\OrderAddresses\JsonApi\V1\OrderAddressSchema::class,
            'route_groups' => [],
        ],

        'order_lines' => [
            'schema' => Dystcz\LunarApi\Domain\OrderLines\JsonApi\V1\OrderLineSchema::class,
            'route_groups' => [],
        ],

        'payment_options' => [
            'schema' => \Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\PaymentOptionSchema::class,
            'route_groups' => [
                'payment_options' => \Dystcz\LunarApi\Domain\PaymentOptions\Http\Routing\PaymentOptionRouteGroup::class,
            ],
        ],

        'prices' => [
            'schema' => Dystcz\LunarApi\Domain\Prices\JsonApi\V1\PriceSchema::class,
            'route_groups' => [],
        ],

        'products' => [
            'schema' => Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Products\Http\Routing\ProductRouteGroup::class,
            ],
            'filters' => Dystcz\LunarApi\Domain\Products\JsonApi\Filters\ProductFilterCollection::class,
        ],

        'product_types' => [
            'schema' => Dystcz\LunarApi\Domain\ProductTypes\JsonApi\V1\ProductTypeSchema::class,
            'route_groups' => [],
        ],

        'shipping_options' => [
            'schema' => Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\ShippingOptionSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\ShippingOptions\Http\Routing\ShippingOptionRouteGroup::class,
            ],
        ],

        'variants' => [
            'schema' => Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema::class,
            'route_groups' => [],
        ],

        'tags' => [
            'schema' => Dystcz\LunarApi\Domain\Tags\JsonApi\V1\TagSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Tags\Http\Routing\TagRouteGroup::class,
            ],
        ],

        'urls' => [
            'schema' => Dystcz\LunarApi\Domain\Urls\JsonApi\V1\UrlSchema::class,
            'route_groups' => [
                Dystcz\LunarApi\Domain\Urls\Http\Routing\UrlRouteGroup::class,
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
