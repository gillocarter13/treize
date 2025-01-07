<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCommande extends Model
{
    use HasFactory;

    protected $table = 'detail_commandes'; // Nom de votre table

    protected $fillable = [
        'id_commande',
        'produits_quantites',
        'quantite_plat',
        'prix_total',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }
    public function plat()
    {
        return $this->belongsTo(Plat::class, 'id_plat', 'id_plat'); // Assurez-vous que les colonnes sont correctes
    }
    
}
