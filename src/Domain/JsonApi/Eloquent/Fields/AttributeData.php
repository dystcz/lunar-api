<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Support\Arr as JsonApiArr;
use LaravelJsonApi\Eloquent\Fields\Attribute;
use Lunar\FieldTypes\Dropdown;
use Lunar\Models\Attribute as AttributeModel;

class AttributeData extends Attribute
{
    private ?Closure $keys = null;

    /**
     * Group attributes.
     */
    protected bool $groupAttributes = false;

    /**
     * Extra attributes.
     *
     * @var Collection<AttributeModel>|null
     */
    protected Collection $attributes;

    /**
     * Attribute constructor.
     *
     * @param  Collection<AttributeModel>  $attributes
     */
    public function __construct(
        string $fieldName,
        ?string $column = null,
        ?Collection $attributes = null,
    ) {
        parent::__construct($fieldName, $column);

        $this->attributes = $attributes ?? new Collection();
    }

    /**
     * Create an array attribute.
     *
     * @param  Collection<AttributeModel>  $attributes
     */
    public static function make(
        string $fieldName,
        ?string $column = null,
        ?Collection $attributes = null,
    ): static {
        return new static($fieldName, $column, $attributes);
    }

    /**
     * Group attributes.
     */
    public function groupAttributes(): self
    {
        $this->groupAttributes = true;

        return $this;
    }

    /**
     * Set extra attributes.
     *
     * @param  Collection<AttributeModel>  $attributes
     */
    public function addAttributes(Collection $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Determine if attributes should be flattened.
     */
    public function flatten(): bool
    {
        return $this->groupAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(object $model): Hash
    {
        $value = parent::serialize($model);

        if (! $this->groupAttributes) {
            return Hash::cast($value);
        }

        if ($model->attributes instanceof Collection && $model->attributes->isNotEmpty()) {
            $value = $model->attributes
                ->where('attribute_type', $model->getMorphClass())
                ->whereIn('handle', array_keys($value->all()))
                ->groupBy(fn (AttributeModel $attribute) => $attribute->attributeGroup->handle)
                ->map(fn (Collection $attributes, string $group) => $attributes->mapWithKeys(function (AttributeModel $attribute) use ($model) {
                    $value = null;

                    if ($attribute->type === Dropdown::class) {
                        $value = Arr::first(Arr::where(
                            $attribute->configuration['lookups'] ?? [],
                            fn ($lookup) => $lookup['value'] === $model->attr($attribute->handle),
                        ));

                        if ($value && $value['label']) {
                            $value = $value['label'];
                        }
                    }

                    $value = $value ?? $model->attr($attribute->handle);

                    return [
                        $attribute->handle => [
                            'name' => $attribute->translate('name'),
                            'value' => $value,
                            'plaintext_value' => strip_tags($value ?? ''),
                        ],
                    ];
                }));
        }

        return Hash::cast($value);
    }

    /**
     * {@inheritDoc}
     */
    protected function deserialize($value): ?array
    {
        $value = parent::deserialize($value);

        if (is_null($value)) {
            return null;
        }

        if ($value && $this->keys) {
            $value = ($this->keys)($value);
        }

        if (is_null($value)) {
            return null;
        }

        return Hash::cast($value)->all();
    }

    /**
     * {@inheritDoc}
     */
    protected function assertValue($value): void
    {
        if ((! is_null($value) && ! is_array($value)) || (! empty($value) && ! JsonApiArr::isAssoc($value))) {
            throw new \UnexpectedValueException(sprintf(
                'Expecting the value of attribute %s to be an associative array.',
                $this->name()
            ));
        }
    }
}
