<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Plat;
use App\Models\Produit;

class CartController extends Controller
{
    public function stores(Request $request)
    {
        try {
            $erreurs = [];
            $quantites = $request->input('quantites');

            // Vérifier si toutes les quantités sont égales à 0
            if (array_sum($quantites) == 0) {
                return redirect()->route('user.commande')
                    ->withErrors('Veuillez sélectionner au moins un plat avant de passer la commande.');
            }

            // Récupérer les plats et leurs prix
            $plats = Plat::all()->keyBy('id_plat');

            // Vérification préalable des stocks
            foreach ($quantites as $id_plat => $quantite) {
                if ($quantite > 0) {
                    $plat = $plats[$id_plat];
                    $produitsUtilises = json_decode($plat->produits_quantites, true);

                    foreach ($produitsUtilises as $produitInfo) {
                        $id_produit = $produitInfo['id_produit'];
                        $quantite_requise = $produitInfo['quantite'] * $quantite;

                        $produit = Produit::find($id_produit);
                        if ($produit && $produit->quantite_stock < $quantite_requise) {
                            $erreurs[] = "Le produit '{$produit->nom}' n'a pas suffisamment de stock pour le plat '{$plat->nom}'.";
                        }
                    }
                }
            }

            // Si des erreurs sont détectées, renvoyer un message sans créer la commande
            if (!empty($erreurs)) {
                return redirect()->route('user.commande')
                    ->withErrors($erreurs);
            }

            // Si les stocks sont suffisants, créer la commande
            $commande = new Commande();
            $commande->status = 'en attente';
            $commande->type = 'simple';
            $commande->save();

            // Ajouter les détails de la commande
            foreach ($quantites as $id_plat => $quantite) {
                if ($quantite > 0) {
                    $plat = $plats[$id_plat];
                    $produitsUtilises = json_decode($plat->produits_quantites, true);

                    $detailCommande = new DetailCommande();
                    $detailCommande->id_commande = $commande->id_commande;
                    $detailCommande->id_plat = $id_plat;
                    $detailCommande->nom_plat = $plat->nom;
                    $detailCommande->quantite = $quantite;
                    $detailCommande->prix_unitaire = $plat->prix;
                    $detailCommande->prix_total = $plat->prix * $quantite;
                    $detailCommande->save();

                    // Mettre à jour le stock des produits
                    foreach ($produitsUtilises as $produitInfo) {
                        $id_produit = $produitInfo['id_produit'];
                        $quantite_requise = $produitInfo['quantite'] * $quantite;

                        $produit = Produit::find($id_produit);
                        if ($produit) {
                            $produit->quantite_stock -= $quantite_requise;
                            $produit->save();
                        }
                    }
                }
            }

            return redirect()->route('user.commande')
                ->with('success', 'Commande créée avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('user.commande')
                ->withErrors('Une erreur est survenue : ' . $e->getMessage());
        }
    }

}
