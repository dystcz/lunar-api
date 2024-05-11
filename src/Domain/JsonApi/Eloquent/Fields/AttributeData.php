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
     * Model attribute override.
     */
    protected bool $modelAttributes = true;

    /**
     * Add plaintext value to response.
     */
    protected bool $plaintextValues = false;

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
    public function groupAttributes(bool $groupAttributes = true): self
    {
        $this->groupAttributes = $groupAttributes;

        return $this;
    }

    /**
     * Enable or disable model attribute override.
     * This means that $model->{$attribute} will have
     * bigger priority than $model->attr($attribute) if defined.
     */
    public function modelAttributes(bool $modelAttributes = true): self
    {
        $this->modelAttributes = $modelAttributes;

        return $this;
    }

    /**
     * Enable or disable plaintext value.
     * This will add a plaintext value to the attribute.
     * Plaintext value is a version of the value with HTML tags stripped.
     * This only applies for fields with richtext configuration option turned on.
     */
    public function plainTextValues(bool $plaintextValues = true): self
    {
        $this->plaintextValues = $plaintextValues;

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

        if ($model->attributes instanceof Collection && $model->attributes->isNotEmpty()) {
            $attributes = $model->attributes
                ->where('attribute_type', $model->getMorphClass())
                ->whereIn('handle', array_keys($value->all()));

            if ($this->groupAttributes) {
                $value = $attributes
                    ->groupBy(fn (AttributeModel $attribute) => $attribute->attributeGroup->handle)
                    ->map(fn (Collection $attributes, string $group) => $this->mapAttributes($attributes, $model));

                return Hash::cast($value);
            }

            $value = $this->mapAttributes($attributes, $model);
        }

        return Hash::cast($value);
    }

    /**
     * Map the attributes.
     *
     * @param  Collection<AttributeModel>  $attributes
     */
    protected function mapAttributes(Collection $attributes, object $model): Collection
    {
        return $attributes->mapWithKeys(function (AttributeModel $attribute) use ($model) {
            $value = match ($attribute->type) {
                Dropdown::class => $this->getDropdownValue($attribute, $model, $this->modelAttributes),
                default => null
            };

            $value = $value ?? $this->getOtherValue($attribute, $model, $this->modelAttributes);

            return [
                $attribute->handle => [
                    'name' => $attribute->translate('name'),
                    'value' => $value,
                    ...($this->plaintextValues ? ['plaintext' => is_string($value) ? trim(strip_tags($value)) : ''] : []),
                ],
            ];
        });
    }

    /**
     * Get other field type value.
     */
    protected function getOtherValue(AttributeModel $attribute, object $model, bool $useModelAttributes): mixed
    {
        $value = $useModelAttributes
            ? $model->getAttribute($attribute->handle) ?? $model->attr($attribute->handle)
            : $model->attr($attribute->handle);

        return $value;
    }

    /**
     * Get the dropdown field type value.
     */
    protected function getDropdownValue(AttributeModel $attribute, object $model, bool $useModelAttributes): mixed
    {
        $value = Arr::first(Arr::where(
            $attribute->configuration['lookups'] ?? [],
            fn ($lookup) => $lookup['value'] === $useModelAttributes
            ? $model->getAttribute($attribute->handle) ?? $model->attr($attribute->handle)
            : $model->attr($attribute->handle)
        ));

        if ($value && $value['label']) {
            $value = $value['label'];
        }

        return $value;
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
