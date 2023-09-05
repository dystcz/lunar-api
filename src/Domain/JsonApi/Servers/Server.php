<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Servers;

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;

abstract class Server extends BaseServer
{
    /**
     * Server constructor.
     */
    public function __construct(AppResolver $app, string $name)
    {
        $this->setBaseUri();

        parent::__construct($app, $name);
    }

    /**
     * Set base server URI.
     */
    protected function setBaseUri(string $path = 'v1'): void
    {
        $prefix = Config::get('lunar-api.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
    }

    /**
     * Get all additional server schemas.
     */
    protected function getAdditionalServerSchemas(): array
    {
        $additionalServers = Config::get('lunar-api.additional_servers');

        $schemas = array_reduce($additionalServers, function ($schemas, $server) {
            $server = (new $server(
                new AppResolver(fn () => app()),
                $this->name()
            ));

            foreach ($server->allSchemas() as $schema) {
                $schemas[] = $schema;
            }

            return $schemas;
        }, []);

        return $schemas;
    }

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            ...$this->getAdditionalServerSchemas(),
        ];
    }

    /**
     * Get all registered schemas.
     */
    public function getSchemas(): array
    {
        return $this->allSchemas();
    }
}
