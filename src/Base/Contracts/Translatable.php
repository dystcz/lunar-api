<?php

namespace Dystcz\LunarApi\Base\Contracts;

interface Translatable
{
    /**
     * Translate a given attribute based on passed locale.
     *
     * @param  string  $attribute
     * @param  string  $locale
     * @return string|null
     */
    public function translate($attribute, $locale = null);

    /**
     * Translate a value from attribute data.
     */
    public function translateAttribute(string $attribute, ?string $locale = null): mixed;

    /**
     * Shorthand to translate an attribute.
     *
     * @return string|null
     */
    public function attr(...$params): mixed;
}
