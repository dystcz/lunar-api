<?php

namespace Dystcz\LunarApi\Support\Config\Data;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Exception;
use Illuminate\Support\Str;
use LaravelJsonApi\Contracts\Schema\Schema;
use Lunar\Base\Traits\HasModelExtending;

class DomainConfig
{
    /**
     * @param  array<int,mixed>  $rest
     * @param  array<int,interface-string>  $controllers
     */
    public function __construct(
        public string $domain,
        public ?string $model = null,
        public ?string $model_contract = null,
        public ?string $policy = null,
        public ?string $schema = null,
        public ?string $resource = null,
        public ?string $query = null,
        public ?string $collection_query = null,
        public ?string $routes = null,
        public array $controllers = [],
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
     *
     * @return class-string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Get model contract.
     *
     * @return interface-string|null
     */
    public function getModelContract(): ?string
    {
        return $this->model_contract;
    }

    /**
     * Get policy class.
     *
     * @return class-string|null
     */
    public function getPolicy(): ?string
    {
        return $this->policy;
    }

    /**
     * Get schema class.
     *
     * @return class-string|null
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * Get resource class.
     *
     * @return class-string|null
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * Get query class.
     *
     * @return class-string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Get collection query class.
     *
     * @return class-string|null
     */
    public function getCollectionQuery(): ?string
    {
        return $this->collection_query;
    }

    /**
     * Get routes class.
     *
     * @return class-string|null
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
     * Check if domain has model contract.
     */
    public function hasModelContract(): bool
    {
        return ! is_null($this->model_contract);
    }

    /**
     * Check if domain has model and lunar model.
     */
    public function swapsModel(): bool
    {
        return $this->hasModel() && $this->hasModelContract();
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
        if (! $this->swapsModel()) {
            return;
        }

        $this->validateClassUses(
            'model',
            $this->getModel(),
            HasModelExtending::class,
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
     * Validate that a class uses a trait.
     *
     * @param  class-string  $class
     * @param  trait-string  $trait
     */
    private function validateClassUses(string $type, string $class, string $trait): void
    {
        $traits = class_uses_recursive($class);

        if (! in_array($trait, $traits)) {
            throw new Exception("{$type} class {$class} does not use {$trait}.");
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
