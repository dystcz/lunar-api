<?php

namespace Dystcz\LunarApi\Domain\Products\Policies;

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ProductPolicy
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
    public function view(?Authenticatable $user, Product $product): bool
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
    public function update(?Authenticatable $user, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, Product $product): bool
    {
        return false;
    }

    /**
     * Authorize a user to view product's associations.
     */
    public function viewAssociations(?Authenticatable $user, Product $product): bool
    {
        return true;
    }

    /**
     * Authorize a user to view product's brand.
     */
    public function viewBrand(?Authenticatable $user, Product $product): bool
    {
        return true;
    }

    /**
     * Authorize a user to view product's cheapest variant.
     */
    public function viewCheapestVariant(?Authenticatable $user, Product $product): bool
    {
        return true;
    }

    /**
     * Authorize a user to view product's default url.
     */
    public function viewDefaultUrl(?Authenticatable $user, Product $product): bool
    {
        return true;
    }
}
