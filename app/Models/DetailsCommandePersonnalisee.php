<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsCommandePersonnalisee extends Model
{
    use HasFactory;
    // Dans le modèle DetailsCommandePersonnalisee
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit'); // Assurez-vous que les clés correspondent
    }

    // Indiquer le nom de la table si ce n'est pas le pluriel de la classe
    protected $table = 'details_commande_personnalisee';

    // Attributs que vous pouvez remplir par affectation de masse
    protected $fillable = [
        'id_commande',        // ID de la commande associée
        'produits_quantites', // Stockage des produits et quantités en JSON
        'quantite_plat',
        'prix_unitaire',      // Prix unitaire du produit
     // Quantité du plat personnalisé
        'prix_total',         // Prix total du plat
    ];
    protected $casts = [
        'produits_quantites' => 'array', // Cast automatique en tableau
    ];
    // Relation avec le modèle Commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    // Vous pouvez éventuellement ajouter une relation avec le modèle Produit
    // Si vous avez besoin d'accéder aux produits spécifiques dans ce détail
    public function produits()
    {
        // En supposant que vous stockez des IDs de produits dans le JSON
        return $this->hasMany(Produit::class, 'id_produit', 'produits_quantites->id_produit');
    }
}
