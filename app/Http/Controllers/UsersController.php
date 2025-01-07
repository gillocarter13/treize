<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\DetailsCommandePersonnalisee;

class UsersController extends Controller
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
        $produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        return view('user.home', compact('totalPlatsVendus', 'sommeEncaissee', 'produits_en_alerte'));
    }


}
