<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    // Relation inverse avec User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // 'id_user' comme clé étrangère
    }
}
