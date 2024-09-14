<?php

namespace Dystcz\LunarApi\Domain\Attributes\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\Attribute as AttributeContract;

class AttributePolicy
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
    public function view(?Authenticatable $user, AttributeContract $attribute): bool
    {
        return true;
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(?Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?Authenticatable $user, AttributeContract $attribute): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, AttributeContract $attribute): bool
    {
        return true;
    }
}
