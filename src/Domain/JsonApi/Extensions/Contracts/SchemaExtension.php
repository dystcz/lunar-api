<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Data\ExtensionValueCollection;
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
interface SchemaExtension extends Extension
{
    /** {@inheritdoc} */
    public function set(string $property, iterable|Relation|Closure $extension): SchemaExtension;

    /** {@inheritdoc} */
    public function get(string $key): iterable;
}
