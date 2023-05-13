<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent\Fields;

use Closure;
use LaravelJsonApi\Core\Json\Hash;
use LaravelJsonApi\Core\Support\Arr;
use LaravelJsonApi\Eloquent\Fields\Attribute;

class MediaConversion extends Attribute
{
    private ?Closure $keys = null;

    /**
     * Create an array attribute.
     */
    public static function make(string $conversion): self
    {
        return new self($conversion);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize(object $model)
    {
        $value = parent::serialize($model);

        return [
            'path' => $model->getPath($this->name()),
            'url' => $model->getFullUrl($this->name()),
            'srcset' => $model->getSrcset($this->name()),
        ];

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
