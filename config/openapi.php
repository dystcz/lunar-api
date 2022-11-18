<?php

return [

    'collections' => [

        'default' => [

            'info' => [
                'title' => config('app.name'),
                'description' => null,
                'version' => '1.0.0',
                'contact' => [],
            ],

            'servers' => [
                [
                    'url' => env('APP_URL'),
                    'description' => null,
                    'variables' => [],
                ],
            ],

            'tags' => [
                [
                    'name' => 'products',
                    'description' => 'Products',
                ],
                [
                    'name' => 'collections',
                    'description' => 'Collections',
                ],
            ],

            'security' => [
                // GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement::create()->securityScheme('JWT'),
            ],

            // Non standard attributes used by code/doc generation tools can be added here
            'extensions' => [
                // 'x-tagGroups' => [
                //     [
                //         'name' => 'General',
                //         'tags' => [
                //             'user',
                //         ],
                //     ],
                // ],
            ],

            // Route for exposing specification.
            // Leave uri null to disable.
            'route' => [
                'uri' => '/openapi',
                'middleware' => [],
            ],

            // Register custom middlewares for different objects.
            'middlewares' => [
                'paths' => [
                    //
                ],
                'components' => [
                    //
                ],
            ],

        ],

    ],

    // Directories to use for locating OpenAPI object definitions.
    'locations' => [
        'callbacks' => [
            app_path('OpenApi/Callbacks'),
            __DIR__.'/../src/Domain',
        ],

        'request_bodies' => [
            app_path('OpenApi/RequestBodies'),
            __DIR__.'/../src/Domain',
        ],

        'responses' => [
            app_path('OpenApi/Responses'),
            __DIR__.'/../src/Domain',
        ],

        'schemas' => [
            app_path('OpenApi/Schemas'),
            __DIR__.'/../src/Domain',
        ],

        'security_schemes' => [
            app_path('OpenApi/SecuritySchemes'),
            __DIR__.'/../src/Domain',
        ],
    ],

];
