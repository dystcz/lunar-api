<?php

namespace Dystcz\LunarApi\Support\Config\Data;

use Exception;
use Illuminate\Support\Str;

class DomainConfig
{
    /**
     * @param  array<string,mixed>  $route_actions
     * @param  array<string,class-string>  $actions
     * @param  array<string,class-string>  $notifications
     * @param  array<string,mixed>  $settings
     */
    public function __construct(
        public ?string $model = null,
        public ?string $model_contract = null,
        public ?string $policy = null,
        public ?string $schema = null,
        public ?string $resource = null,
        public ?string $query = null,
        public ?string $collection_query = null,
        public ?string $routes = null,
        public array $route_actions = [],
        public array $actions = [],
        public array $notifications = [],
        public array $settings = [],
    ) {
        $this->validate();
    }

    /**
     * Check if domain has schema.
     */
    public function hasSchema(): bool
    {
        return ! is_null($this->schema);
    }

    /**
     * Check if domain has model.
     */
    public function hasModel(): bool
    {
        return ! is_null($this->model);
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
        return ! is_null($this->policy);
    }

    /**
     * Check if domain has routes.
     */
    public function hasRoutes(): bool
    {
        return ! is_null($this->routes);
    }

    /**
     * Validate domain config.
     */
    public function validate(): void
    {
        $this->validateClassExistence();

        // TODO: Add remaining checks
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
