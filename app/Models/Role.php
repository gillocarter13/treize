<?php
// App\Models\Role.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role'; // Si la clé primaire est 'id_role'

    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}
