<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\ExtensionCollection;

/**
 * @method ExtensionCollection fields(array $fields = [])
 * @method ExtensionCollection filters(array $filters = [])
 * @method ExtensionCollection sortables(array $sortables = [])
 * @method ExtensionCollection with(array $with = [])
 * @method ExtensionCollection includePaths(array $includePaths = [])
 */
class SchemaExtension extends Extension
{
    protected ExtensionCollection $fields;
    protected ExtensionCollection $filters;
    protected ExtensionCollection $sortables;
    protected ExtensionCollection $with;
    protected ExtensionCollection $includePaths;

    public function __construct()
    {
        $this->fields = new SchemaExtensionCollection();
        $this->filters = new SchemaExtensionCollection();
        $this->sortables = new SchemaExtensionCollection();
        $this->with = new SchemaExtensionCollection();
        $this->includePaths = new SchemaExtensionCollection();
    }
}