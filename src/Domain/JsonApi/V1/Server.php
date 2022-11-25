<?php

namespace Dystcz\LunarApi\Domain\JsonApi\V1;

use Dystcz\LunarApi\Domain\Products\JsonApi\Schemas\ProductSchema;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;

class Server extends BaseServer
{
    /**
     * Server constructor.
     *
     * @param AppResolver $app
     * @param string $name
     */
    public function __construct(AppResolver $app, string $name)
    {
        $this->baseUri = '/' . Config::get('lunar-api.route_prefix') . '/v1';

        parent::__construct($app, $name);
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            ProductSchema::class,
        ];
    }

    /**
     * Determine if the server is authorizable.
     *
     * @return bool
     */
    public function authorizable(): bool
    {
        // TODO: Write policies
        return false;
    }
}