<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Servers;

use Dystcz\LunarApi\Base\Facades\SchemaManifestFacade;
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
        $prefix = Config::get('lunar-api.general.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
    }

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return SchemaManifestFacade::getRegisteredSchemas()->values()->toArray();
    }
}
