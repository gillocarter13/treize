<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAdminDashboard(User $user)
    {
        return $user->role->id_role == 1; // Assurez-vous que 1 correspond au rôle admin
    }
}
