<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Employe;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Vérifiez le rôle et créez un enregistrement correspondant
        if ($user->role_id == 1) { // Admin
            Admin::create([
                'id_user' => $user->id_user,
                // Ajoutez d'autres attributs si nécessaire
            ]);
        } elseif ($user->role_id == 2) { // Employé
            Employe::create([
                'id_user' => $user->id_user,
                // Ajoutez d'autres attributs si nécessaire
            ]);
        }
    }
}
