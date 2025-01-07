<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';
    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'nom',
        'type',
        'unité_de_mesure',
        'id_fournisseur',
        'quantité',
        'prix_unitaire',
        'prix_total',
        'seuil_alerte',
        'date_commande',
        'quantite_stock',
    ];

    // Relation avec Fournisseur
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id_fournisseur');
    }
    public function updateStock($quantite)
    {
        $this->quantite_stock -= $quantite;
        $this->save();
    }
    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'plat_produit', 'id_produit', 'id_plat')
        ->withPivot('quantite'); // Utilisation du champ 'quantite' de la table pivot
    }
    public function commandes()
    {
        return $this->belongsToMany(Commande::class)
            ->withPivot('quantite'); // Pour stocker la quantité dans la table pivot
    }
}
