<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Equipment;
use App\Models\User;

/**
 * Class EquipmentPolicy
 *
 * This class defines the authorization policy for managing equipment. It specifies the rules
 * for determining whether a user can perform various actions related to equipment, such as viewing,
 * creating, updating, and deleting them.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class EquipmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Equipment $equipment): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user != null && $user->validated && $user->hasrole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user != null && $user->validated && $user->hasrole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user != null && $user->validated && $user->hasrole('admin');
    }
}
