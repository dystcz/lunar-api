<?php

namespace Dystcz\LunarApi\Domain\Discounts\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\Discount as DiscountContract;

class DiscountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?Authenticatable $user, DiscountContract $model): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
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
    public function update(?Authenticatable $user, DiscountContract $model): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, DiscountContract $model): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return false;
    }
}
