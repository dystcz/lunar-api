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

    // Route groups which get registered
    // If you want to change the behaviour or add some data,
    // simply extend the package product groups and add your logic
    'route_groups' => [
        'products' => Dystcz\LunarApi\Domain\Products\Http\Routing\ProductsRouteGroup::class,
        'collections' => Dystcz\LunarApi\Domain\Collections\Http\Routing\CollectionsRouteGroup::class,
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
