<?php

namespace Dystcz\LunarApi\Domain\Addresses\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasCompanyIdentifiersInMeta
{
    /**
     * Get the company in.
     */
    public function companyIn(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['company_in'] ?? null,
        );
    }

    /**
     * Get the company in.
     */
    public function companyTin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['company_tin'] ?? null,
        );
    }
}
