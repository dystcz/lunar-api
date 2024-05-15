<?php

namespace Dystcz\LunarApi\Base\Contracts;

interface Translatable
{
    /**
     * Translate a given attribute based on passed locale.
     *
     * @param  string  $attribute
     * @param  string  $locale
     * @return string
     */
    public function translate($attribute, $locale = null);

    /**
     * Translate a value from attribute data.
     *
     * @param  string  $attribute
     * @param  string  $locale
     * @return string
     */
    public function translateAttribute($attribute, $locale = null);

    /**
     * Shorthand to translate an attribute.
     *
     * @param  mixed  $params
     * @return void
     */
    public function attr(...$params);
}
