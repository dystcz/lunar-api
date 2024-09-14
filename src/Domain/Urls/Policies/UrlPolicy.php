<?php

namespace Dystcz\LunarApi\Domain\Urls\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\Url as UrlContract;

class UrlPolicy
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
    public function view(?Authenticatable $user, UrlContract $url): bool
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
    public function update(?Authenticatable $user, UrlContract $url): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, UrlContract $url): bool
    {
        return true;
    }
}
