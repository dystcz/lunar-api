<?php

namespace Dystcz\LunarApi\Tests\Stubs\Policies;

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TestBrandPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, Brand $brand): bool
    {
        return false;
    }
}
