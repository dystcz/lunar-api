<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

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
    public function view(?Authenticatable $user, ProductVariantContract $variant): bool
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
    public function update(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Authorize a user to view variant's default url.
     */
    public function viewDefaultUrl(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's images.
     */
    public function viewImages(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view other variants.
     */
    public function viewOtherProductVariants(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's prices.
     */
    public function viewPrices(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's lowest price.
     */
    public function viewLowestPrice(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's highest price.
     */
    public function viewHighestPrice(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's product.
     */
    public function viewProduct(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's thumbnail.
     */
    public function viewThumbnail(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }

    /**
     * Authorize a user to view variant's urls.
     */
    public function viewUrls(?Authenticatable $user, ProductVariantContract $variant): bool
    {
        return true;
    }
}
