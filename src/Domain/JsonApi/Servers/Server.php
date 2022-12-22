<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Servers;

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;

abstract class Server extends BaseServer
{
    /**
     * Server constructor.
     *
     * @param AppResolver $app
     * @param string $name
     */
    public function __construct(AppResolver $app, string $name)
    {
        $this->setBaseUri();

        parent::__construct($app, $name);
    }

    /**
     * Set base server URI.
     *
     * @param string $path
     * @return void
     */
    protected function setBaseUri(string $path = 'v1'): void
    {
        $prefix = Config::get('lunar-api.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
    }

    /**
     * Get additional server schemas.
     *
     * @return array
     */
    protected function getAdditionalServerSchemas(): array
    {
        $additionalServers = Config::get('lunar-api.additional_servers');

        // $schemas = array_reduce($additionalServers, function ($schemas, $server) {
        //     $schemas[] = dd((new $server(new AppResolver(fn () => app()), $this->name()))->schemas());
        //
        //     return $schemas;
        // }, []);
        //
        // dd($schemas);

        return [];
    }

    /**
     * Get all registered schemas.
     *
     * @return array
     */
    public function getSchemas(): array
    {
        return $this->allSchemas();
    }
}
