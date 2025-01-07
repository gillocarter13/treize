<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueCommande extends Model
{
    use HasFactory;

    protected $table = 'historique_commande'; // Nom de la table
    use HasFactory;

    protected $fillable = [
        'id_produit',      // Ajoutez cette ligne
        'id_fournisseur',
        'quantité',
        'prix_unitaire',
        'prix_total',
        'seuil_alerte',
        'created_at',      // Incluez ceci si vous souhaitez que cela soit géré automatiquement
    ];
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', ownerKey: 'id_produit'); // Assurez-vous que les clés étrangères sont correctes
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id_fournisseur'); // Assurez-vous que les clés étrangères sont correctes
    }
}
