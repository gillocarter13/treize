aaaaa<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin'; // Utilisation de la guard 'admin'

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_user', // Clé étrangère vers la table 'users'
    ];

    /**
     * Les attributs qui doivent être masqués pour les tableaux.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relation avec le modèle User.
     *
     * Un Admin appartient à un User (table parent).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // 'id_user' comme clé étrangère
    }
}
