<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriqueCommande;

class DashboardController extends Controller
{
    // Méthode pour afficher les données du tableau de bord avec filtre
    public function totalAchats(Request $request)
    {
        // Récupérer le filtre depuis l'URL
        $filtre = $request->input('filtre', 'aujourdhui');  // Valeur par défaut 'aujourdhui'

        // Définir les dates de début et de fin en fonction du filtre
        switch ($filtre) {
            case 'aujourdhui':
                $date_debut = now()->startOfDay();
                $date_fin = now()->endOfDay();
                break;
            case 'mois':
                $date_debut = now()->startOfMonth();
                $date_fin = now()->endOfMonth();
                break;
            case 'annee':
                $date_debut = now()->startOfYear();
                $date_fin = now()->endOfYear();
                break;
            default:
                $date_debut = now()->startOfDay();
                $date_fin = now()->endOfDay();
                break;
        }

        // Calculer le montant total des achats pour la période donnée
        $totalAchats = HistoriqueCommande::whereBetween('created_at', [$date_debut, $date_fin])
            ->sum('prix_total');

        // Retourner la vue avec les données du tableau de bord
        return view('admin.home', compact('totalAchats', 'filtre'));
    }
}
