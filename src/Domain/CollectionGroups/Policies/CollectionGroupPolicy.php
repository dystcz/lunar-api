<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\CollectionGroup as CollectionGroupContract;

class CollectionGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, CollectionGroupContract $group): bool
    {
        return true;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(?Authenticatable $user): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Authenticatable $user, CollectionGroupContract $group): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, CollectionGroupContract $group): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }
}
