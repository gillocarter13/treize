<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\DetailCommande;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $filtreVentes = $request->input('filtreVentes', 'aujourdhui');

        $date_debut_ventes = match ($filtreVentes) {
            'semaine' => now()->startOfWeek(),
            'mois' => now()->startOfMonth(),
            'annee' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $date_fin_ventes = match ($filtreVentes) {
            'semaine' => now()->endOfWeek(),
            'mois' => now()->endOfMonth(),
            'annee' => now()->endOfYear(),
            default => now()->endOfDay(),
        };

        // Calcul des ventes
        $totalAchats = DetailCommande::whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes])
            ->sum('prix_total'); // Supposons que 'prix_total' contient le montant total pour chaque achat

        // Obtenir d'autres données nécessaires
        $topPlats = DetailCommande::select('id_plat', DB::raw('SUM(quantite) as total_vendu'))
        ->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes])
            ->groupBy('id_plat')
            ->orderByDesc('total_vendu')
            ->take(5)
            ->with('plat')
            ->get();


        // Vérifier les produits en alerte
        $produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        // Filtrage des dates pour les achats
        $filtreAchats = $request->input('filtreAchats', 'aujourdhui');
        $date_achats = $request->input('date_achats');

        if ($date_achats) {
            $date_debut_achats = Carbon::parse($date_achats)->startOfDay();
            $date_fin_achats = Carbon::parse($date_achats)->endOfDay();
        } else {
            $date_debut_achats = match ($filtreAchats) {
                'mois' => now()->startOfMonth(),
                'annee' => now()->startOfYear(),
                default => now()->startOfDay(),
            };
            $date_fin_achats = match ($filtreAchats) {
                'mois' => now()->endOfMonth(),
                'annee' => now()->endOfYear(),
                default => now()->endOfDay(),
            };
        }

        $totalAchats = Produit::whereBetween('created_at', [$date_debut_achats, $date_fin_achats])
            ->sum('prix_total');

        // Filtrage des dates pour les ventes
        $filtreVentes = $request->input('filtreVentes', 'aujourdhui');
        $date_ventes = $request->input('date_ventes');

        if ($date_ventes) {
            $date_debut_ventes = Carbon::parse($date_ventes)->startOfDay();
            $date_fin_ventes = Carbon::parse($date_ventes)->endOfDay();
        } else {
            $date_debut_ventes = match ($filtreVentes) {
                'mois' => now()->startOfMonth(),
                'annee' => now()->startOfYear(),
                default => now()->startOfDay(),
            };
            $date_fin_ventes = match ($filtreVentes) {
                'mois' => now()->endOfMonth(),
                'annee' => now()->endOfYear(),
                default => now()->endOfDay(),
            };
        }

        $totalPlatsVendus = 0;
        $sommeEncaissee = 0;

        // Calcul des ventes pour les commandes simples
        $commandesSimples = Commande::where('type', 'simple')
            ->whereHas('details', function ($query) use ($date_debut_ventes, $date_fin_ventes) {
                $query->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes]);
            })
            ->with(['details' => function ($query) use ($date_debut_ventes, $date_fin_ventes) {
                $query->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes]);
            }])  // Charger les détails des commandes
            ->get();

        foreach ($commandesSimples as $commande) {
            foreach ($commande->details as $detail) {
                $totalPlatsVendus += $detail->quantite;
                $sommeEncaissee += $detail->prix_total;
            }
        }

        // Calcul des ventes pour les commandes personnalisées
        $commandesPersonnalisees = Commande::where('type', 'personalisee')
            ->whereHas('details_personnalises', function ($query) use ($date_debut_ventes, $date_fin_ventes) {
                $query->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes]);
            })
            ->with(['details_personnalises' => function ($query) use ($date_debut_ventes, $date_fin_ventes) {
                $query->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes]);
            }])
            ->get();

        foreach ($commandesPersonnalisees as $commande) {
            foreach ($commande->details_personnalises as $detail) {
                $totalPlatsVendus += $detail->quantite_plat;
                $sommeEncaissee += $detail->prix_total;
            }
        }

        // Récupérer les 5 plats les plus vendus directement via la relation des commandes et leurs détails
        $filtreVentes = $request->input('filtreVentes', 'aujourdhui');
        $date_ventes = $request->input('date_ventes');

        if ($date_ventes) {
            $date_debut_ventes = Carbon::parse($date_ventes)->startOfDay();
            $date_fin_ventes = Carbon::parse($date_ventes)->endOfDay();
        } else {
            $date_debut_ventes = match ($filtreVentes) {
                'mois' => now()->startOfMonth(),
                'annee' => now()->startOfYear(),
                default => now()->startOfDay(),
            };
            $date_fin_ventes = match ($filtreVentes) {
                'mois' => now()->endOfMonth(),
                'annee' => now()->endOfYear(),
                default => now()->endOfDay(),
            };
        }

        // Obtenir les 5 plats les plus vendus en fonction des ventes dans la période sélectionnée
        $topPlats = DetailCommande::select('id_plat', DB::raw('SUM(quantite) as total_vendu'))
        ->whereBetween('created_at', [$date_debut_ventes, $date_fin_ventes])
        ->groupBy('id_plat')
        ->orderByDesc('total_vendu')
        ->take(5)
        ->with('plat') // Charger la relation 'plat' pour obtenir les détails
        ->get()
        ->map(function ($detail) {
            return [
                'nom_plat' => $detail->plat->nom ?? 'Nom non disponible',
                'prix' => $detail->plat->prix ?? 0,
                'quantite_vendue' => $detail->total_vendu,
                'revenue_total' => ($detail->plat->prix ?? 0) * $detail->total_vendu,
            ];
        });



        return view('admin.home', compact(
            'produits_en_alerte',
            'totalAchats',
            'filtreAchats',
            'sommeEncaissee',
            'filtreVentes',
            'totalPlatsVendus',
            'topPlats',
            'totalAchats'
             // Passer les plats les plus vendus à la vue
        ));
    }

    public function createUser()
    {
        if (auth()->check() && auth()->user()->role->id_role == 1) {
            $roles = Role::all(); // Récupère tous les rôles

            return view('admin.create_user', compact('roles'));
        } else {
            return redirect('/login');
        }
    }
}
