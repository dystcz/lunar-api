<?php

namespace Dystcz\LunarApi\Domain\Addresses\Policies;

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Address $address): bool
    {
        return $this->update($user, $address);
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Address $address): bool
    {
        return $user->customers()
            ->where((new Customer)->getTable().'.id', $address->customer_id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Address $address): bool
    {
        return $this->update($user, $address);
    }
}
