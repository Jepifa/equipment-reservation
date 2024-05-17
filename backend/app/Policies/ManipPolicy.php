<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Manip;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Class ManipPolicy
 *
 * This class defines the authorization policy for managing manipulations. It specifies the rules
 * for determining whether a user can perform various actions related to manipulations, such as viewing,
 * creating, updating, and deleting them.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class ManipPolicy
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
    public function view(User $user, Manip $manip): bool
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
    public function update(User $user, Manip $manip): bool
    {
        return $user != null && $user->validated && ($user->hasrole('admin') || $manip->user_id == $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Manip $manip): bool
    {
        return $user != null && $user->validated && ($user->hasrole('admin') || $manip->user_id === $user->id);
    }
}
