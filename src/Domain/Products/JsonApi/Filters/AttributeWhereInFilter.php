<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\Filters;

use Illuminate\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasColumn;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasDelimiter;

class AttributeWhereInFilter implements Filter
{
    use HasColumn;
    use HasDelimiter;
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
        $values = $this->deserialize($value);
        // dd($values);

        $query = $query->whereIn(
            "{$column}->{$this->attribute}->value",
            $values
        );

        foreach ($values as $value) {
            $query->orWhereJsonContains(
                "{$column}->{$this->attribute}->value",
                $value
            );
        }

        return $query;
    }

    /**
     * Deserialize the fitler value.
     *
     * @param string|array $value
     * @return array
     */
    protected function deserialize($value): array
    {
        if ($this->deserializer) {
            return ($this->deserializer)($value);
        }

        return $this->toArray($value);
    }
}
