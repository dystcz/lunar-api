<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions;

use BadMethodCallException;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\ExtensionStore;
use Illuminate\Support\Traits\ForwardsCalls;
use InvalidArgumentException;
use RuntimeException;

abstract class Extension
{
    use ForwardsCalls;

    /**
     * Extended class.
     *
     * @var class-string
     */
    protected string $class;

    /**
     * Extension store.
     */
    protected ExtensionStore $store;

    /**
     * @param  class-string<Extendable>  $class
     */
    public function __construct(string $class)
    {
        $this->setExtendable($class);
    }

    /**
     * Set extendable class.
     *
     * @param  class-string<Extendable>  $class
     */
    protected function setExtendable(string $class): void
    {
        if (is_subclass_of($class, Extendable::class)) {
            $this->class = $class;

            return;
        }

        throw new RuntimeException("{$class} cannot be extended.");
    }

    /**
     * Set extension value.
     */
    protected function set(string $property, mixed $value): self
    {
        if (is_iterable($value)) {
            foreach ($value as $item) {
                throw_if(is_iterable($item), new InvalidArgumentException('Extension cannot be nested.'));

                array_push($this->store->{$property}, $item);
            }

            return $this;
        }

        array_push($this->store->{$property}, $value);

        return $this;
    }

    /**
     * Get extension value.
     */
    protected function get(string $property): array
    {
        if (is_null($this->store->{$property})) {
            return [];
        }

        return $this->store->{$property};
    }

    /**
     * Dynamically call setter or getter.
     */
    public function __call(string $method, array $arguments): mixed
    {
        if (! property_exists($this->store, $method)) {
            throw new BadMethodCallException("{$method} is not extendable.");
        }

        $action = count($arguments) > 0 ? 'set' : 'get';

        return $this->{$action}($method, ...$arguments);
    }

    /**
     * Get manifest caller.
     */
    public static function caller(): string
    {
        return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['class'];
    }
}
