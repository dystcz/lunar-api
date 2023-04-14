<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Resources;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Contracts\Resources\Serializer\Attribute as SerializableAttribute;
use LaravelJsonApi\Contracts\Resources\Serializer\Relation as SerializableRelation;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Core\Resources\JsonApiResource as BaseApiResource;

class JsonApiResource extends BaseApiResource implements Extendable
{
    /**
     * Resource extension.
     */
    protected ResourceExtension $extension;

    /**
     * JsonApiResource constructor.
     *
     * @param  Schema  $schema
     * @param  object  $resource
     */
    public function __construct(protected Schema $schema, public object $resource)
    {
        $this->extension = ResourceManifest::for(static::class);

        parent::__construct($schema, $resource);
    }

    protected function hasExtension(): bool
    {
        return $this->extension->hasExtension();
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

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        foreach ($this->allAttributes() as $attr) {
            if ($attr instanceof AttributeData && $request->has('attribute_data')) {
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
        }
    }

    /**
     * Get extended resource's attributes.
     *
     * @return iterable
     */
    protected function allAttributes(): iterable
    {
        yield from $this->schema->attributes();

        yield from $this->extension->attributes();
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        foreach ($this->allRelationships() as $relation) {
            if ($relation instanceof SerializableRelation && $relation->isNotHidden($request)) {
                yield $relation->serializedFieldName() => $this->serializeRelation($relation);
            }

            if ($relation instanceof Closure) {
                $relation = Closure::bind($relation, $this, self::class);

                yield from $relation($this);
            }
        }
    }

    /**
     * Get extended resource's attributes.
     *
     * @return iterable
     */
    protected function allRelationships(): iterable
    {
        yield from $this->schema->relationships();

        yield from $this->extension->relationships();
    }
}
