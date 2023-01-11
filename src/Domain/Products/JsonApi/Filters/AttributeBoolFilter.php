<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use Illuminate\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasColumn;

class AttributeBoolFilter implements Filter
{
    use HasColumn;
    use DeserializesValue;

    /**
     * @var string
     */
    private string $attribute;

    /**
     * @var string
     */
    private string $name;

    /**
     * Create a new filter.
     *
     * @param string $name
     * @param string|null $attribute
     * @return static
     */
    public static function make(string $name, ?string $attribute = null): self
    {
        return new static($name, $attribute);
    }

    /**
     * CustomFilter constructor.
     *
     * @param string $name
     * @param string|null $attribute
     */
    public function __construct(string $name, ?string $attribute = null)
    {
        $this->name = $name;
        $this->column = 'attribute_data';
        $this->attribute = $attribute ?? Str::snake($this->name);
    }

    /**
     * @inheritDoc
     */
    public function key(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isSingular(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function apply($query, $value)
    {
        $column = $query->getModel()->qualifyColumn($this->column());
        $value = $this->deserialize($value);

        return $query->where(
            "{$column}->{$this->attribute}->value",
            $value ? 'true' : 'false'
        );
    }

    /**
     * Deserialize the fitler value.
     *
     * @param string|array $value
     * @return bool
     */
    protected function deserialize($value): bool
    {
        if ($this->deserializer) {
            return ($this->deserializer)($value);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }
}
