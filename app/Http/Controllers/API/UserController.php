<?php

namespace App\Http\Controllers\Api;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\DetailsCommandePersonnalisee;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
public function index()
{
$aujourdhui = now()->format('Y-m-d'); // Date du jour

// Récupération des commandes régulières et personnalisées
$commandes = Commande::with('details')
->whereDate('created_at', $aujourdhui)
->get();
$commandesPersonnalisees = DetailsCommandePersonnalisee::whereHas('commande', function ($query) use ($aujourdhui) {
$query->whereDate('created_at', $aujourdhui)->where('type', 'personalisee');
})->get();

$totalPlatsVendus = 0;
$sommeEncaissee = 0;

// Calcul des totaux pour les commandes régulières
foreach ($commandes as $commande) {
foreach ($commande->details as $detail) {
$totalPlatsVendus += $detail->quantite;
$sommeEncaissee += $detail->prix_total;
}
}

// Calcul des totaux pour les commandes personnalisées
foreach ($commandesPersonnalisees as $detail) {
$totalPlatsVendus += $detail->quantite_plat;
$sommeEncaissee += $detail->prix_total;
}

// Vérifier les alertes de stock
$produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte' )->get();

    // Retourner les données sous forme de réponse JSON
    return response()->json([
    'totalPlatsVendus' => $totalPlatsVendus,
    'sommeEncaissee' => number_format($sommeEncaissee, 2, ',', ' '),
    'produitsEnAlerte' => $produits_en_alerte->map(function ($produit) {
    return [
    'nom' => $produit->nom,
    'quantite_stock' => $produit->quantite_stock,
    'seuil_alerte' => $produit->seuil_alerte
    ];
    })
    ]);
    }
    }
