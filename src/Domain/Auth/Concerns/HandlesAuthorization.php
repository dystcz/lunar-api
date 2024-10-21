<?php

namespace Dystcz\LunarApi\Domain\Auth\Concerns;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Access\HandlesAuthorization as BaseHandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

trait HandlesAuthorization
{
    use BaseHandlesAuthorization;

    protected function isFilamentAdmin(?Authenticatable $user): bool
    {
        return $user instanceof FilamentUser;
    }
}
