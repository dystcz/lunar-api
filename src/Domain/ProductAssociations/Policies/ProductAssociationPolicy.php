<?php

namespace Dystcz\LunarApi\Domain\ProductAssociations\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\ProductAssociation as ProductAssociationContract;

class ProductAssociationPolicy
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
    public function view(?Authenticatable $user, ProductAssociationContract $association): bool
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
    public function update(?Authenticatable $user, ProductAssociationContract $association): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, ProductAssociationContract $association): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Authorize a user to view associations's parent.
     */
    public function viewParent(?Authenticatable $user, ProductAssociationContract $product): bool
    {
        return true;
    }

    /**
     * Authorize a user to view associations's target.
     */
    public function viewTarget(?Authenticatable $user, ProductAssociationContract $product): bool
    {
        return true;
    }
}
