<?php

namespace Dystcz\LunarApi\Base\Extensions;

use Closure;
use Dystcz\LunarApi\Base\Contracts\Extendable;
use Dystcz\LunarApi\Base\Contracts\SchemaExtension as SchemaExtensionContract;
use Dystcz\LunarApi\Base\Data\ExtensionValue;
use Dystcz\LunarApi\Base\Data\ExtensionValueCollection;
use InvalidArgumentException;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;

/**
 * @property  class-string<Extendable>  $class
 *
 * @method ExtensionValueCollection with()
 * @method self setWith(iterable|callable $value)
 * @method ExtensionValueCollection includePaths()
 * @method self setIncludePaths(iterable|callable $value)
 * @method ExtensionValueCollection fields()
 * @method self setFields(iterable|callable $value)
 * @method ExtensionValueCollection filters()
 * @method self setFilters(iterable|callable $value)
 * @method ExtensionValueCollection sortables()
 * @method self setSortables(iterable|callable $value)
 * @method ExtensionValueCollection showRelated()
 * @method self setShowRelated(iterable|callable $value)
 * @method ExtensionValueCollection showRelationship()
 * @method self setShowRelationship(iterable|callable $value)
 */
class SchemaExtension extends Extension implements SchemaExtensionContract
{
    /**
     * @param  class-string<Extendable>  $class
     */
    public function __construct(
        protected string $class,
        /**
         * With property extensions.
         */
        protected ExtensionValueCollection $with = new ExtensionValueCollection,

        /**
         * IncludePaths method extensions.
         */
        protected ExtensionValueCollection $includePaths = new ExtensionValueCollection,

        /**
         * Fields method extensions.
         */
        protected ExtensionValueCollection $fields = new ExtensionValueCollection,

        /**
         * Filters method extensions.
         */
        protected ExtensionValueCollection $filters = new ExtensionValueCollection,

        /**
         * Sortables method extensions.
         */
        protected ExtensionValueCollection $sortables = new ExtensionValueCollection,

        /**
         * ShowRelated extensions.
         */
        protected ExtensionValueCollection $showRelated = new ExtensionValueCollection,

        /**
         * ShowRelationship extensions.
         */
        protected ExtensionValueCollection $showRelationship = new ExtensionValueCollection,
    ) {
        parent::__construct($class);
    }

    /**
     * Set extension value.
     *
     * Closure will be called with Extendable
     * instance as argument and Closure will be bound to its scope,
     * so you can use $this to refference the Extendable instance.
     *
     * @param  iterable|Relation|Closure(ExtendableContract):((array))  $value
     */
    public function set(string $property, iterable|Relation|Closure $extension): self
    {
        // dump($property, $extension);
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
}
