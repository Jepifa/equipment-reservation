<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;

/**
 * Class UserPolicy
 *
 * This class defines the authorization policy for managing users. It specifies the rules
 * for determining whether a user can perform various actions related to users, such as viewing,
 * creating, updating, and deleting them.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        $authUser = auth()->user();
        return $authUser != null && $authUser->validated;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user != null && $user->validated;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        $authUser = auth()->user();
        return $authUser != null && $authUser->validated && $authUser->hasrole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user != null && $user->validated && $user->hasrole('admin');
    }
}
