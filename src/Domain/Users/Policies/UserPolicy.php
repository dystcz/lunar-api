<?php

namespace Dystcz\LunarApi\Domain\Users\Policies;

use Dystcz\LunarApi\Domain\Users\Contracts\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserPolicy
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
    public function view(?Authenticatable $user, User $model): bool
    {
        return $model->id === $user?->id;
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
    public function update(?Authenticatable $user, User $model): bool
    {
        return $model->id === $user?->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, User $model): bool
    {
        return $this->update($user, $model);
    }

    /**
     * Authorize a user to view a user's orders.
     */
    public function viewOrders(User $user, User $model): bool
    {
        return $model->id === $user?->id;
    }
}
