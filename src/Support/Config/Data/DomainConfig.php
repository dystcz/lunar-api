<?php

namespace Dystcz\LunarApi\Support\Config\Data;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Exception;
use Illuminate\Support\Str;
use LaravelJsonApi\Contracts\Schema\Schema;
use Lunar\Base\BaseModel;

class DomainConfig
{
    /**
     * @param  array<int,mixed>  $rest
     */
    public function __construct(
        public string $domain,
        public ?string $model = null,
        public ?string $lunar_model = null,
        public ?string $policy = null,
        public ?string $schema = null,
        public ?string $resource = null,
        public ?string $query = null,
        public ?string $collection_query = null,
        public ?string $routes = null,
        array ...$rest,
    ) {
        $this->validate();
    }

    /**
     * Get domain name.
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Get model class.
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Get lunar model class.
     */
    public function getLunarModel(): ?string
    {
        return $this->lunar_model;
    }

    /**
     * Get policy class.
     */
    public function getPolicy(): ?string
    {
        return $this->policy;
    }

    /**
     * Get schema class.
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * Get resource class.
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * Get query class.
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Get collection query class.
     */
    public function getCollectionQuery(): ?string
    {
        return $this->collection_query;
    }

    /**
     * Get routes class.
     */
    public function getRoutes(): ?string
    {
        return $this->routes;
    }

    /**
     * Check if domain has schema.
     */
    public function hasSchema(): bool
    {
        return ! is_null($this->getSchema());
    }

    /**
     * Check if domain has resource.
     */
    public function hasResource(): bool
    {
        return ! is_null($this->getResource());
    }

    /**
     * Check if domain has model.
     */
    public function hasModel(): bool
    {
        return ! is_null($this->getModel());
    }

    /**
     * Check if domain has lunar model.
     */
    public function hasLunarModel(): bool
    {
        return ! is_null($this->getLunarModel());
    }

    /**
     * Check if domain has model and lunar model.
     */
    public function swapsLunarModel(): bool
    {
        return $this->hasModel() && $this->hasLunarModel();
    }

    /**
     * Check if domain has policy.
     */
    public function hasPolicy(): bool
    {
        return ! is_null($this->getPolicy());
    }

    /**
     * Check if domain has routes.
     */
    public function hasRoutes(): bool
    {
        return ! is_null($this->getRoutes());
    }

    /**
     * Validate domain config.
     */
    public function validate(): void
    {
        $this->validateClassExistence();
        $this->validateModel();
        $this->validateSchema();
        $this->validateResource();
        $this->validateRoutes();

        // ...
    }

    /**
     * Validate existence of classes.
     */
    private function validateClassExistence(): void
    {
        $checks = [
            'model',
            // TODO: Validate Lunar model contracts after Lunar SP was booted
            // 'lunar_model',
            'policy',
            'schema',
            'resource',
            'query',
            'collection_query',
            'routes',
        ];

        foreach ($checks as $type) {
            if (! $this->{$type}) {
                continue;
            }

            $this->validateClassExists($type, $this->{$type});
        }
    }

    /**
     * Validate model.
     */
    private function validateModel(): void
    {
        if (! $this->swapsLunarModel()) {
            return;
        }

        $this->validateSubclassOf(
            'model',
            $this->getModel(),
            BaseModel::class,
        );
    }

    /**
     * Validate schema.
     */
    private function validateSchema(): void
    {
        if (! $this->hasSchema()) {
            return;
        }

        $this->validateClassImplements(
            'schema',
            $this->getSchema(),
            Schema::class,
        );
    }

    /**
     * Validate resource.
     */
    private function validateResource(): void
    {
        if (! $this->hasResource()) {
            return;
        }

        $this->validateSubclassOf(
            'resource',
            $this->getResource(),
            JsonApiResource::class,
        );
    }

    /**
     * Validate domain route groups.
     */
    private function validateRoutes(): void
    {
        if (! $this->hasRoutes()) {
            return;
        }

        $this->validateClassImplements(
            'routes',
            $this->getRoutes(),
            RouteGroupContract::class,
        );
    }

    /**
     * Validate that a class exists.
     *
     * @param  class-string  $class
     */
    private function validateClassExists(string $type, string $class): void
    {
        $type = Str::upper($type);

        if (! class_exists($class)) {
            throw new Exception("{$type} class {$class} does not exist.");
        }
    }

    /**
     * Validate that a class is a subclass of another class.
     *
     * @param  class-string  $class
     * @param  class-string  $subclass
     */
    private function validateSubclassOf(string $type, string $class, string $subclass): void
    {
        $type = Str::upper($type);

        if (! is_subclass_of($class, $subclass)) {
            throw new Exception("{$type} class {$class} must be a subclass of {$subclass}.");
        }
    }

    /**
     * Validate that a class implements an interface.
     *
     * @param  class-string  $class
     * @param  class-string  $interface
     */
    private function validateClassImplements(string $type, string $class, string $interface): void
    {
        $type = Str::upper($type);

        if (! in_array($interface, class_implements($class))) {
            throw new Exception("{$type} class {$class} must implement {$interface}.");
        }
    }
}
