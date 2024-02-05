<?php

namespace Dystcz\LunarApi\Domain\Attributes\Actions;

use Dystcz\LunarApi\Domain\Attributes\Models\Attribute;
use Dystcz\LunarApi\Domain\Products\JsonApi\Filters\AttributeBoolFilter;
use Dystcz\LunarApi\Domain\Products\JsonApi\Filters\AttributeWhereInFilter;
use Dystcz\LunarApi\Support\Actions\Action;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Where;

class GetAttributeFilter extends Action
{
    /**
     * Get suitable filter for an attribute.
     */
    public function handle(Attribute $attribute): Filter
    {
        return match ($attribute->type) {
            \Lunar\FieldTypes\Text::class => Where::make($attribute->handle),
            \Lunar\FieldTypes\TranslatedText::class => Where::make($attribute->handle),
            \Lunar\FieldTypes\Toggle::class => AttributeBoolFilter::make($attribute->handle),
            \Lunar\FieldTypes\Dropdown::class => AttributeWhereInFilter::make($attribute->handle)->delimiter(','),
            // \Dystcz\LunarMultiselect\Multiselect::class => AttributeWhereInFilter::make($attribute->handle)->delimiter(','),
            default => $this->guessFilter($attribute)
        };
    }

    /**
     * Guess filter for an attribute.
     */
    protected function guessFilter(Attribute $attribute): Filter
    {
        /** @var Collection $config */
        $config = $attribute->configuration;

        if ($config->has('lookups')) {
            return AttributeWhereInFilter::make($attribute->handle)->delimiter(',');
        }

        if ($config->has('on_value') && $config->has('off_value')) {
            return AttributeBoolFilter::make($attribute->handle);
        }

        return Where::make($attribute->handle);
    }
}
