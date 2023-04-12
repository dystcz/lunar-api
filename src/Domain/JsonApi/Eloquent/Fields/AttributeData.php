<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use Closure;
use Illuminate\Support\Collection;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Support\Arr;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use Lunar\Models\Attribute;

class AttributeData extends ArrayHash
{
    /**
     * @var Closure|null
     */
    private ?Closure $keys = null;

    /**
     * @var int|null
     */
    private ?int $sortValues = null;

    /**
     * @var int|null
     */
    private ?int $sortKeys = null;

    /**
     * The JSON:API field case.
     *
     * @var string|null
     */
    private ?string $fieldCase = null;

    /**
     * The database key-case.
     *
     * @var string|null
     */
    private ?string $keyCase = null;

    /**
     * Group attributes.
     *
     * @var bool
     */
    private bool $groupAttributes = false;

    /**
     * Create an array attribute.
     *
     * @param  string  $fieldName
     * @param  string|null  $column
     * @return AttributeData
     */
    public static function make(string $fieldName, string $column = null): self
    {
        return new self($fieldName, $column);
    }

    /**
     * Group attributes.
     *
     * @return self
     */
    public function groupAttributes(): self
    {
        $this->groupAttributes = true;

        return $this;
    }

    /**
     * Determine if attributes should be flattened.
     *
     * @return bool
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

        if ($this->groupAttributes && $model->attributes instanceof Collection) {
            $value = $model->attributes
                ->where('attribute_type', $model->getMorphClass())
                ->whereIn('handle', array_keys($value->all()))
                ->groupBy(fn (Attribute $attribute) => $attribute->attributeGroup->handle)
                ->map(fn ($attributes) => $attributes->mapWithKeys(fn (Attribute $attribute) => [
                    $attribute->handle => [
                        'name' => $attribute->translate('name'),
                        'value' => $model->attr($attribute->handle),
                    ],
                ]));
        }

        return Hash::cast($value)
            ->maybeSorted($this->sortValues)
            ->maybeSortKeys($this->sortKeys)
            ->useCase($this->fieldCase);
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

        return Hash::cast($value)
            ->maybeSorted($this->sortValues)
            ->maybeSortKeys($this->sortKeys)
            ->useCase($this->keyCase)
            ->all();
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
