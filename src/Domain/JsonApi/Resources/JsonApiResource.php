<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Resources;

use Closure;
use Dystcz\LunarApi\Base\Contracts\Extendable as ExtendableContract;
use Dystcz\LunarApi\Base\Contracts\ResourceExtension as ResourceExtensionContract;
use Dystcz\LunarApi\Base\Contracts\ResourceManifest as ResourceManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Resources\Serializer\Attribute as SerializableAttribute;
use LaravelJsonApi\Contracts\Resources\Serializer\Relation as SerializableRelation;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Core\Resources\JsonApiResource as BaseApiResource;
use LaravelJsonApi\Core\Resources\Relation;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class JsonApiResource extends BaseApiResource implements ExtendableContract
{
    private static array $types = [];

    /**
     * Resource extension.
     */
    protected ResourceExtensionContract $extension;

    /**
     * JsonApiResource constructor.
     */
    public function __construct(
        protected Schema $schema,
        public object $resource,
    ) {
        $this->extension = App::make(ResourceManifestContract::class)::for(static::class);

        parent::__construct($schema, $resource);
    }

    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Model $model */
        $model = $this->resource;

        foreach ($this->allAttributes($request) as $key => $attr) {
            if ($attr instanceof AttributeData && $request?->has('attribute_data')) {
                $attr = $attr->serializeUsing(
                    static fn ($value) => $value->only(explode(',', $request->get('attribute_data')))
                );
            }

            if ($attr instanceof AttributeData && $attr->flatten()) {
                foreach ($attr->serialize($this->resource) as $key => $attr) {
                    yield $key => $attr;
                }
            }

            if ($attr instanceof SerializableAttribute && $attr->isNotHidden($request)) {
                yield $attr->serializedFieldName() => $attr->serialize($this->resource);
            }

            if (is_scalar($attr) && is_scalar($key)) {
                yield $key => $attr;
            }
        }
    }

    /**
     * Get all resource's attributes.
     *
     * @param  Request|null  $request
     * @return array<int,mixed>
     */
    protected function allAttributes($request): iterable
    {
        return [
            ...$this->schema->attributes(),
            ...$this->extendedFields($this->extension->attributes()->all()),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     */
    public function relationships($request): iterable
    {
        foreach ($this->allRelationships($request) as $relation) {
            if ($relation instanceof Relation && ! $relation instanceof SerializableRelation) {
                yield $relation->fieldName() => $relation;
            }

            if ($relation instanceof SerializableRelation && $relation->isNotHidden($request)) {
                yield $relation->serializedFieldName() => $this->serializeRelation($relation);
            }
        }
    }

    /**
     * Get all resource's relationships.
     *
     * @param  Request|null  $request
     * @return array<int,mixed>
     */
    protected function allRelationships($request): iterable
    {
        return [
            ...$this->schema->relationships(),
            ...$this->extendedFields($this->extension->relationships()->all()),
        ];
    }

    /**
     * Get extended resource's relationships.
     *
     * @param  array<int,mixed>  $fields
     */
    protected function extendedFields(array $fields): array
    {
        $fields = array_map(function ($field) {
            $field = $field->value();

            if ($field instanceof Closure) {
                $field = Closure::bind($field, $this, parent::class);

                return $field($this);
            }

            return $field;
        }, $fields);

        $recursiveArrayIterator = new RecursiveArrayIterator($fields, RecursiveArrayIterator::CHILD_ARRAYS_ONLY);
        $iterator = new RecursiveIteratorIterator($recursiveArrayIterator);

        return iterator_to_array($iterator);
    }

    /**
     * Get the model key.
     *
     * @return string|int
     */
    private function modelKey()
    {
        if ($key = $this->schema->idKeyName()) {
            return $this->resource->{$key};
        }

        if ($this->resource instanceof UrlRoutable) {
            return $this->resource->getRouteKey();
        }

        throw new LogicException('Resource is not URL routable: you must implement the id method yourself.');
    }
}
