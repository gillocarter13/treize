<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseur'; // Spécifiez le nom de la table si différent
    protected $primaryKey = 'id_fournisseur'; // Spécifiez la clé primaire

    protected $fillable = ['nom', 'contact', 'adresse']; // Autorisez les champs à être remplis
}
