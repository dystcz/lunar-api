<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields\AttributeData;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use LaravelJsonApi\Contracts\Resources\Serializer\Attribute;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Core\Resources\JsonApiResource as BaseApiResource;
use LaravelJsonApi\Core\Resources\Relation;

class JsonApiResource extends BaseApiResource
{
    /**
     * JsonApiResource constructor.
     *
     * @param  Schema  $schema
     * @param  object  $resource
     */
    public function __construct(Schema $schema, object $resource)
    {
        parent::__construct($schema, $resource);
    }

    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Product $model */
        $model = $this->resource;

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        $attributes = [
            ...$this->schema->attributes(),
            ...ResourceManifest::for(static::class)->attributes(),
        ];

        foreach ($attributes as $attr) {
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

            if ($attr instanceof Attribute && $attr->isNotHidden($request)) {
                yield $attr->serializedFieldName() => $attr->serialize($this->resource);
            }
        }
    }

    /**
     * Create a new resource relation.
     */
    public function relation(string $fieldName, string $keyName = null): Relation
    {
        return parent::relation($fieldName, $keyName);
    }
}
