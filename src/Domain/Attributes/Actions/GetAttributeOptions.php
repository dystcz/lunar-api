<?php

namespace Dystcz\LunarApi\Domain\Attributes\Actions;

use Dystcz\LunarApi\Domain\Attributes\Models\Attribute;
use Dystcz\LunarApi\Domain\Filters\Data\FilterOption;
use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Support\Collection;

class GetAttributeOptions extends Action
{
    /**
     * Get options from an attribute.
     */
    public function handle(Attribute $attribute): ?array
    {
        return match ($attribute->type) {
            \Lunar\FieldTypes\Text::class => null,
            \Lunar\FieldTypes\TranslatedText::class => null,
            \Lunar\FieldTypes\Toggle::class => $this->getBooleanOptions($attribute),
            \Lunar\FieldTypes\Dropdown::class => $this->getLookupOptions($attribute),
            // \Dystcz\LunarMultiselect\Multiselect::class => [],
            default => $this->guessOptions($attribute),
        };
    }

    /**
     * Get lookup options from an attribute.
     */
    protected function getLookupOptions(Attribute $attribute): array
    {
        /** @var Collection $config */
        $config = $attribute->configuration;

        if (! $config->has('lookups')) {
            return [];
        }

        $lookups = Collection::make($config->get('lookups'))
            ->map(fn (array $lookup) => new FilterOption(
                label: $lookup['label'],
                value: $lookup['value'] ? $lookup['value'] : $lookup['label'],
            ))
            ->toArray();

        return $lookups;
    }

    /**
     * Get boolean options from an attribute.
     */
    protected function getBooleanOptions(Attribute $attribute): array
    {
        /** @var Collection $config */
        $config = $attribute->configuration;

        if (! $config->has('on_value') || ! $config->has('off_value')) {
            return [];
        }

        return [
            new FilterOption(
                label: 'on',
                value: $config->get('on_value'),
            ),
            new FilterOption(
                label: 'off',
                value: $config->get('off_value'),
            ),
        ];
    }

    /**
     * Guess options from an attribute.
     */
    protected function guessOptions(Attribute $attribute): ?array
    {
        /** @var Collection $config */
        $config = $attribute->configuration;

        if ($config->has('lookups')) {
            return $this->getLookupOptions($attribute);
        }

        if ($config->has('on_value') && $config->has('off_value')) {
            return $this->getBooleanOptions($attribute);
        }

        return null;
    }
}
