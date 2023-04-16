<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Policies;

use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProductVariantPolicy
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
    public function view(?Authenticatable $user, ProductVariant $variant): bool
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
    public function update(?Authenticatable $user, ProductVariant $variant): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, ProductVariant $variant): bool
    {
        return false;
    }

    /**
     * Authorize a user to view variant's product.
     */
    public function viewProduct(?Authenticatable $user, ProductVariant $variant): bool
    {
        return false;
    }
}
