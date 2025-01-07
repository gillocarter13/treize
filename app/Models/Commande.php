<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';
    protected $primaryKey = 'id_commande';
    public $timestamps = true;

    protected $fillable = ['status', 'type'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class)
            ->withPivot('quantite'); // Ajoutez 'quantite' si vous stockez la quantité commandée
    }
    public function details()
    {
        return $this->hasMany(DetailCommande::class, 'id_commande'); // Assurez-vous que la clé étrangère est correcte
    }
    public function plats()
    {
        return $this->belongsToMany(Plat::class)->withPivot('quantite');
    }
    public function plat()
    {
        return $this->belongsTo(Plat::class, 'id_plat'); // Vérifiez que 'id_plat' est la bonne clé étrangère
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }
    public function details_personnalises()
    {
        return $this->hasMany(DetailsCommandePersonnalisee::class, 'id_commande');
    }
    
}
