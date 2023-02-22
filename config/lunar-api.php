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

    // Configuration for specific domains
    'domains' => [
        'associations' => [
            'route_groups' => [
                //
            ],

            'pagination' => 12,
        ],

        'brands' => [
            'route_groups' => [
                'brands' => Dystcz\LunarApi\Domain\Brands\Http\Routing\BrandRouteGroup::class,
            ],

            'pagination' => 12,
        ],

        'collections' => [
            'route_groups' => [
                'collections' => Dystcz\LunarApi\Domain\Collections\Http\Routing\CollectionRouteGroup::class,
            ],

            'pagination' => 12,
        ],

        'prices' => [
            'model' => Dystcz\LunarApi\Domain\Prices\Models\Price::class,

            'route_groups' => [
                //
            ],

            'pagination' => 12,
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

            // Default pagination
            'pagination' => 12,
        ],

        'carts' => [
            'model' => \Dystcz\LunarApi\Domain\Carts\Models\Cart::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'carts' => \Dystcz\LunarApi\Domain\Carts\Http\Routing\CartRouteGroup::class,
            ],

            // Default pagination
            'pagination' => 12,
        ],

        'cart_lines' => [
            'model' => \Dystcz\LunarApi\Domain\Carts\Models\CartLine::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'cart_lines' => \Dystcz\LunarApi\Domain\Carts\Http\Routing\CartLineRouteGroup::class,
            ],

            // Default pagination
            'pagination' => 12,
        ],

        'cart_addresses' => [
            'route_groups' => [
                'cart_addresses' => \Dystcz\LunarApi\Domain\Carts\Http\Routing\CartAddressRouteGroup::class,
            ],

            // Default pagination
            'pagination' => 12,
        ],

        'customers' => [
            'model' => \Dystcz\LunarApi\Domain\Customers\Models\Customer::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'customers' => \Dystcz\LunarApi\Domain\Customers\Http\Routing\CustomerRouteGroup::class,
            ],

            // Default pagination
            'pagination' => 12,
        ],

        'orders' => [
            'model' => \Dystcz\LunarApi\Domain\Orders\Models\Order::class,

            // Route groups which get registered
            // If you want to change the behaviour or add some data,
            // simply extend the package product groups and add your logic
            'route_groups' => [
                'orders' => \Dystcz\LunarApi\Domain\Orders\Http\Routing\OrderRouteGroup::class,
            ],

            // Default pagination
            'pagination' => 12,
        ],

        'order_lines' => [
            'model' => \Dystcz\LunarApi\Domain\Orders\Models\OrderLine::class,

            // Default pagination
            'pagination' => 12,
        ],

        'urls' => [
            'route_groups' => [
                'urls' => Dystcz\LunarApi\Domain\Urls\Http\Routing\UrlRouteGroup::class,
            ],

            'pagination' => 12,
        ],

        'variants' => [
            'model' => Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant::class,

            'route_groups' => [
                //
            ],

            'pagination' => 12,
        ],
    ],

    'taxation' => [
        'prices_with_default_tax' => true,
    ],

    // OpenAPI config
    'openapi' => [
        'yaml_generate' => true, // Generate YAML file for OpenAPI spec?
        'yaml_file_name' => 'openapi.yaml', // Name of the YAML OpenAPI spec file
        'json_generate' => true, // Generate JSON file for OpenAPI spec?
        'json_file_name' => 'openapi.json', // Name of the JSON OpenAPI spec file
        'folder_path' => 'openapi', // This is where the generated files will be stored
    ],

];
