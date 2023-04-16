<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Schema\IncludePathIterator;
use LaravelJsonApi\Core\Server\Server;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema as BaseSchema;

abstract class Schema extends BaseSchema implements Extendable
{
    /**
     * The maximum depth of include paths.
     */
    protected int $maxDepth = 0;

    /**
     * The default paging parameters to use if the client supplies none.
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * {@inheritDoc}
     */
    public static function resource(): string
    {
        return Config::get('lunar-api.domains.'.static::type().'resource', parent::resource());
    }

    /**
     * Schema extension.
     */
    protected SchemaExtension $extension;

    /**
     * Schema constructor.
     */
    public function __construct(Server $server)
    {
        $this->extension = SchemaManifest::for(static::class);

        $this->server = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function with(): array
    {
        $paths = array_merge(parent::with(), Arr::wrap($this->with));

        return array_values(array_unique($paths));
    }

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        if (0 < $this->maxDepth) {
            return new IncludePathIterator(
                $this->server->schemas(),
                $this,
                $this->maxDepth
            );
        }

        return [
            ...$this->extension->includePaths(),

            ...parent::includePaths(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): iterable
    {
        return $this->extension->fields();
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): iterable
    {
        return [
            WhereIdIn::make($this),

            ...$this->extension->filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return $this->extension->sortables();
    }

    /**
     * {@inheritDoc}
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(
                Config::get('lunar-api.default_pagination', 12)
            );
    }
}
