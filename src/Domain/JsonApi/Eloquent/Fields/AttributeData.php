<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use Closure;
use Illuminate\Support\Collection;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Support\Arr;
use LaravelJsonApi\Eloquent\Fields\Attribute;
use Lunar\Models\Attribute as AttributeModel;

class AttributeData extends Attribute
{
    private ?Closure $keys = null;

    /**
     * Group attributes.
     */
    private bool $groupAttributes = false;

    /**
     * Create an array attribute.
     */
    public static function make(string $fieldName, string $column = null): self
    {
        return new self($fieldName, $column);
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
     * Determine if attributes should be flattened.
     */
    public function flatten(): bool
    {
        return $this->groupAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(object $model)
    {
        $value = parent::serialize($model);

        if (! $value || ! $model->attributes instanceof Collection || ! $this->groupAttributes) {
            return Hash::cast($value);
        }

        $value = $model->attributes
            ->where('attribute_type', $model->baseModelClass())
            ->whereIn('handle', array_keys($value->all()))
            ->groupBy(fn (AttributeModel $attribute) => $attribute->attributeGroup->handle)
            ->map(fn ($attributes) => $attributes->mapWithKeys(fn (AttributeModel $attribute) => [
                $attribute->handle => [
                    'name' => $attribute->translate('name'),
                    'value' => $model->attr($attribute->handle),
                ],
            ]));

        return Hash::cast($value);
    }

    /**
     * {@inheritDoc}
     */
    protected function deserialize($value)
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
        if ((! is_null($value) && ! is_array($value)) || (! empty($value) && ! Arr::isAssoc($value))) {
            throw new \UnexpectedValueException(sprintf(
                'Expecting the value of attribute %s to be an associative array.',
                $this->name()
            ));
        }
    }
}
