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
        public ?string $lunar_model = null,
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
     * Check if domain has routes.
     */
    public function hasRoutes(): bool
    {
        return $this->routes && $this->schema;
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
            'lunar_model',
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
