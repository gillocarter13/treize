<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;

    protected $table = 'plat';
    protected $primaryKey = 'id_plat';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'produits_quantites',
            'is_active',
        'image',

    ];
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
    public function details()
    {
        return $this->hasMany(DetailCommande::class, 'id_plat'); // Clé étrangère
    }
    protected $casts = [
        'produits_quantites' => 'array',
    ];
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'plat_produit', 'id_plat', 'id_produit')
        ->withPivot('quantite'); // Utilisation du champ 'quantite' de la table pivot
    }
    public function plat()
    {
        return $this->belongsTo(Plat::class, 'id_plat'); // Assurez-vous que la clé étrangère est correcte
    }
}
