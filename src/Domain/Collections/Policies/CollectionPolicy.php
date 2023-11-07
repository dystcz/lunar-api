<?php

namespace Dystcz\LunarApi\Domain\Collections\Policies;

use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CollectionPolicy
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
    public function view(?Authenticatable $user, Collection $collection): bool
    {
        return true;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(?Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Authenticatable $user, Collection $collection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Collection $collection): bool
    {
        return false;
    }

    /**
     * Authorize a user to view collections's products.
     */
    public function viewProducts(?Authenticatable $user, Collection $collection): bool
    {
        return true;
    }

    /**
     * Authorize a user to view collection's default url.
     */
    public function viewDefaultUrl(?Authenticatable $user, Collection $collection): bool
    {
        return true;
    }
}
