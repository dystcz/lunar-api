<?php

namespace Dystcz\LunarApi\Domain\ProductOptions\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\ProductOption as ProductOptionContract;

class ProductOptionPolicy
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
    public function view(?Authenticatable $user, ProductOptionContract $productOption): bool
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
    public function update(?Authenticatable $user, ProductOptionContract $productOption): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, ProductOptionContract $productOption): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Authorize a user to view variant's product option values.
     */
    public function viewProductOptionValues(?Authenticatable $user, ProductOptionContract $productOption): bool
    {
        return true;
    }
}
