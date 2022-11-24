<?php

namespace Dystcz\LunarApi\Domain\JsonApi\OpenApi\Schemas;

use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\CurrencyJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\JsonApi\Builders\JsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\MediaJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\PriceJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductVariantJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Builders\UrlJsonApiBuilder;
use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class JsonApiSchema
{
    /**
     * When set to true, the schema for data property will be generated as an array of objects.
     */
    protected bool $collection = false;

    /**
     * Map of builders.
     */
    protected array $builders = [
        CollectionJsonApiBuilder::class,
        UrlJsonApiBuilder::class,
        ProductJsonApiBuilder::class,
        MediaJsonApiBuilder::class,
        ProductVariantJsonApiBuilder::class,
        PriceJsonApiBuilder::class,
        CurrencyJsonApiBuilder::class,
    ];

    public function __construct(protected string $modelClass)
    {
    }

    public static function model(string $modelClass): static
    {
        return new static($modelClass);
    }

    /**
     * Find the builder for the given model.
     */
    public function getBuilderByModel(): JsonApiBuilder
    {
        foreach ($this->builders as $builder) {
            if ($builder::$model === $this->modelClass) {
                return new $builder();
            }
        }

        throw new \RuntimeException("Builder for model {$this->modelClass} not found.");
    }

    /**
     * Set the collection flag.
     */
    public function collection(): static
    {
        $this->collection = true;

        return $this;
    }

    /**
     * Generate the schema for the given model.
     */
    public function generate(): MediaType|SchemaContract
    {
        return MediaType::json()->schema(
            Schema::object()->properties(
                $this->getDataSchema(),
                $this->getIncludedSchema(),
                $this->getJsonApiSchema(),
            )
        );
    }

    /**
     * Generate the schema for the data property.
     */
    protected function getDataSchema(): SchemaContract
    {
        if ($this->collection) {
            return Schema::array('data')->items(
                $this->getModelSchema()
            );
        }

        return Schema::array('data')->items(
            $this->getModelSchema()
        );
    }

    /**
     * Generate the schema for the included property.
     */
    protected function getIncludedSchema(): Schema
    {
        return Schema::array('included')->items(
            $this->getRelationshipsSchema()
        );
    }

    /**
     * Generate the schema for the jsonapi property.
     */
    protected function getJsonApiSchema(): Schema
    {
        return Schema::object('jsonapi')->properties(
            Schema::string('version')->example('1.0'),
            Schema::object('meta')->properties(),
        );
    }

    /**
     * Find the schema for the model.
     */
    protected function getModelSchema(): SchemaContract
    {
        return $this->getBuilderByModel()::$schema::ref();
    }

    /**
     * Prepare the schemas for model's relationships.
     */
    protected function getRelationshipsSchema(): SchemaContract
    {
        $builder = $this->getBuilderByModel();

        $schemas = [];

        /** @var IncludeElement $includeElement */
        foreach ($builder->includes() as $includeElement) {
            $schemas[] = $includeElement->getBuilderClass()::$schema::ref();
        }

        return AnyOf::create()->schemas(...$schemas);
    }
}
