<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->is_admin || $currentUser->id === $user->id;
    }
    
    public function delete(User $currentUser, User $user)
    {
        return $currentUser->is_admin;
    }
    
}
