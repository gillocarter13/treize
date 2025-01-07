<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Plat;
use App\Models\Produit;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validation des données reçues
            $request->validate([
                'details_commande' => 'required|array',
                'details_commande.*.id_plat' => 'required|integer|exists:plat,id_plat',
                'details_commande.*.quantite' => 'required|integer|min:1',
            ]);

            $detailsCommande = $request->input('details_commande');

            // Vérification préalable des stocks
            $erreursStock = [];
            foreach ($detailsCommande as $item) {
                $id_plat = $item['id_plat'];
                $quantite = $item['quantite'];

                $plat = Plat::findOrFail($id_plat);
                $produitsUtilises = json_decode($plat->produits_quantites, true);

                foreach ($produitsUtilises as $produitInfo) {
                    $id_produit = $produitInfo['id_produit'];
                    $quantite_requise = $produitInfo['quantite'] * $quantite;

                    $produit = Produit::find($id_produit);
                    if ($produit && $produit->quantite_stock < $quantite_requise) {
                        $erreursStock[] = [
                            'produit_nom' => $produit->nom,
                            'produit_unite_de_mesure' => $produit->unité_de_mesure,

                            'plat_nom' => $plat->nom,
                            'quantite_stock' => $produit->quantite_stock,
                            'quantite_requise' => $quantite_requise,

                        ];
                    }
                }
            }

            // Retourner une erreur si des stocks sont insuffisants
            if (!empty($erreursStock)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Stock insuffisant pour certains produits.',
                    'erreurs' => $erreursStock
                ], 400);
            }

            // Création de la commande si tous les stocks sont suffisants
            $commande = new Commande();
            $commande->status = 'en attente';
            $commande->type = 'simple';
            $commande->save();

            foreach ($detailsCommande as $item) {
                $id_plat = $item['id_plat'];
                $quantite = $item['quantite'];

                $plat = Plat::findOrFail($id_plat);
                $produitsUtilises = json_decode($plat->produits_quantites, true);
                $prix_unitaire = $plat->prix;
                $prix_total = $prix_unitaire * $quantite;

                // Créer un détail de commande
                $detailCommande = new DetailCommande();
                $detailCommande->id_commande = $commande->id_commande;
                $detailCommande->id_plat = $id_plat;
                $detailCommande->nom_plat = $plat->nom;
                $detailCommande->quantite = $quantite;
                $detailCommande->prix_unitaire = $prix_unitaire;
                $detailCommande->prix_total = $prix_total;
                $detailCommande->save();

                // Mettre à jour le stock des produits utilisés
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

            return response()->json([
                'status' => 201,
                'message' => 'Commande créée avec succès !',
                'commande_id' => $commande->id_commande,
                'details_commande' => $detailsCommande,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la commande : ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Erreur interne du serveur : ' . $e->getMessage()
            ], 500);
        }
    }

}
