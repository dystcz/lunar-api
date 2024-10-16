<?php

namespace Dystcz\LunarApi\Domain\Addresses\Policies;

use Dystcz\LunarApi\Domain\Auth\Concerns\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lunar\Models\Contracts\Address as AddressContract;
use Lunar\Models\Customer;

class AddressPolicy
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
    public function view(?Authenticatable $user, AddressContract $address): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $address);
    }

    /**
     * Determine whether the user can view address country.
     */
    public function viewCountry(?Authenticatable $user, AddressContract $address): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $address);
    }

    /**
     * Determine whether the user can view address customer.
     */
    public function viewCustomer(?Authenticatable $user, AddressContract $address): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $address);
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
    public function update(?Authenticatable $user, AddressContract $address): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $address);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?Authenticatable $user, AddressContract $address): bool
    {
        if ($this->isFilamentAdmin($user)) {
            return true;
        }

        return $this->check($user, $address);
    }

    /**
     * Determine whether the user can access the model.
     */
    public function check(?Authenticatable $user, AddressContract $address): bool
    {
        $customersTable = (new (Customer::modelClass()))->getTable();

        /** @var User $user */
        return $user
            ->customers()
            ->where("{$customersTable}.id", $address->customer_id)
            ->exists();
    }
}
