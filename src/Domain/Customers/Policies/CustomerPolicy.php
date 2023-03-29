<?php

namespace Dystcz\LunarApi\Domain\Customers\Policies;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Customer $customer): bool
    {
        return $this->update($user, $customer);
    }

    /**
     * Determine if the given user can create posts.
     */
    public function create(Authenticatable $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Customer $customer): bool
    {
        return $user->customers()
            ->where((new Customer)->getTable().'.id', $customer->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Customer $customer): bool
    {
        return $this->update($user, $customer);
    }

    /**
     * Authorize a user to view a customer's orders.
     */
    public function viewOrders(Authenticatable $user, Customer $customer): bool
    {
        return $this->update($user, $customer);
    }
}
