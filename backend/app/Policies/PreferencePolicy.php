<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Preference;
use App\Models\User;

/**
 * Class PreferencePolicy
 *
 * This class defines the authorization policy for managing preferences. It specifies the rules
 * for determining whether a user can perform various actions related to preferences, such as viewing,
 * creating, updating, and deleting them.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class PreferencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyByUser(User $user): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Preference $preference): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Preference $preference): bool
    {
        return $user != null && $user->validated && ($user->hasrole('admin') || $preference->user_id == $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Preference $preference): bool
    {
        return $user != null && $user->validated && ($user->hasrole('admin') || $preference->user_id === $user->id);
    }
}
