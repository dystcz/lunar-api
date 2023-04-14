<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Eloquent\Schema as BaseSchema;

abstract class Schema extends BaseSchema implements Extendable
{
    /**
     * Schema extension.
     */
    protected SchemaExtension $extension;

    /**
     * Schema constructor.
     *
     * @param  Server  $server
     */
    public function __construct(Server $server)
    {
        // dd(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2));
        $this->extension = SchemaManifest::for(static::class);

        $this->server = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function with(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [];

        foreach ($this->extension->includePaths() as $path) {
            yield $path;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return [];

        foreach ($this->extension->fields() as $field) {
            yield $field;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): iterable
    {
        return [];

        // yield from $this->extension->filters();
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return [];

        // yield from $this->extension->sortables();
    }
}
