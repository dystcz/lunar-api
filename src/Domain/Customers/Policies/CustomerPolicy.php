<?php

namespace Dystcz\LunarApi\Domain\Customers\Policies;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
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
        return $this->check($user, $customer);
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
        return $this->check($user, $customer);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Customer $customer): bool
    {
        return $this->check($user, $customer);
    }

    /**
     * Authorize a user to view a customer's addresses.
     */
    public function viewAddresses(Authenticatable $user, Customer $customer): bool
    {
        return $this->check($user, $customer);
    }

    /**
     * Authorize a user to view a customer's orders.
     */
    public function viewOrders(Authenticatable $user, Customer $customer): bool
    {
        return $this->check($user, $customer);
    }

    /**
     * Determine whether the user can view the model.
     */
    protected function check(Authenticatable $user, Customer $customer): bool
    {
        $customersTable = (new Customer)->getTable();

        /** @var User $user */
        return $user
            ->customers()
            ->where("{$customersTable}.id", $customer->id)
            ->exists();
    }
}
