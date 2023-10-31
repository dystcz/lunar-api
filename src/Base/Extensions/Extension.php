<?php

namespace Dystcz\LunarApi\Base\Extensions;

use BadMethodCallException;
use Closure;
use Dystcz\LunarApi\Base\Contracts\Extendable as ExtendableContract;
use Dystcz\LunarApi\Base\Contracts\Extension as ExtensionContract;
use Dystcz\LunarApi\Base\Data\ExtensionValue;
use Dystcz\LunarApi\Base\Data\ExtensionValueCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use InvalidArgumentException;

abstract class Extension implements ExtensionContract
{
    use ForwardsCalls;

    /**
     * Extended class.
     *
     * @var class-string
     */
    protected string $class;

    /**
     * @param  class-string<ExtendableContract>  $class
     */
    public function __construct(string $class)
    {
        $this->setExtendable($class);
    }

    /**
     * Set extendable class.
     *
     * @param  class-string<ExtendableContract>  $class
     */
    protected function setExtendable(string $class): void
    {
        if (is_subclass_of($class, ExtendableContract::class)) {
            $this->class = $class;

            return;
        }

        throw new InvalidArgumentException("{$class} cannot be extended.");
    }

    /**
     * Set extension value.
     *
     * Closure will be called with Extendable
     * instance as argument and Closure will be bound to its scope,
     * so you can use $this to refference the Extendable instance.
     *
     * @param  iterable|Closure(ExtendableContract):((array))  $value
     */
    public function set(string $property, iterable|Closure $extension): self
    {
        /** @var ExtensionValueCollection $collection */
        $collection = $this->{$property};

        if (is_iterable($extension)) {
            foreach ($extension as $value) {
                throw_if(is_iterable($value), new InvalidArgumentException('Extension value cannot be nested.'));

                $collection->push(ExtensionValue::from($value));
            }

            return $this;
        }

        $collection->push(ExtensionValue::from($extension));

        return $this;
    }

    /**
     * Get property extension values.
     */
    public function get(string $property): ExtensionValueCollection
    {
        /** @var ExtensionValueCollection $collection */
        $collection = $this->{$property};

        return $collection;
    }

    /**
     * Dynamically call setter or getter.
     */
    public function __call(string $method, $arguments): mixed
    {

        $property = Str::of($method)->after('set')->camel()->toString();
        $action = $property === $method ? $action = 'get' : 'set';

        if (! property_exists($this, $property)) {
            throw new BadMethodCallException("{$property} is not extendable.");
        }

        if ($action === 'set' && empty($arguments)) {
            throw new BadMethodCallException("You are trying to set property {$property} without a value.");
        }

        return $this->forwardCallTo($this, $action, [$property, ...$arguments]);
    }

    /**
     * Get manifest caller.
     */
    public static function caller(): string
    {
        return debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['class'];
    }
}
