<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CommandeController extends Controller
{
    public function index()
    {
        // Récupérer les plats uniquement actifs
        $plats = Plat::where('is_active', true)->get();

        // Récupérer tous les produits
        $produits = Produit::all();

        // Récupérer les commandes du jour avec leurs détails
        $commandes = Commande::with('details')
        ->whereDate('created_at', Carbon::today()) // Filtrer par la date du jour
            ->orderByRaw("FIELD(status, 'en attente', 'terminé')")
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer les produits en alerte (stock inférieur au seuil)
        $produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        // Retourner la vue avec les données nécessaires
        return view('user.commande', compact('plats', 'commandes', 'produits', 'produits_en_alerte'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commandes' => 'required|array',
            'commandes.*.id_plat' => 'required|integer|exists:plat,id_plat',
            'commandes.*.quantite' => 'required|integer|min:1',
        ]);

        $produitsQuantites = [];

        try {
            foreach ($validatedData['commandes'] as $commandeData) {
                $id_plat = $commandeData['id_plat'];
                $quantite_plat = $commandeData['quantite'];
                $plat = Plat::findOrFail($id_plat);
                $produitsUtilises = json_decode($plat->produits_quantites, true);

                foreach ($produitsUtilises as $produitInfo) {
                    $id_produit = $produitInfo['id_produit'];
                    $quantite_produit = $produitInfo['quantite'] * $quantite_plat;

                    $produit = Produit::findOrFail($id_produit);
                    if ($produit->quantite_stock < $quantite_produit) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stock insuffisant pour le produit : ' . $produit->nom,
                        ], 400);
                    }

                    $produitsQuantites[$id_produit] = ($produitsQuantites[$id_produit] ?? 0) + $quantite_produit;
                }
            }

            $commande = null;

            DB::transaction(function () use (&$commande, $validatedData, $produitsQuantites) {
                $commande = Commande::create([
                    'status' => 'en attente',
                    'type' => 'simple',
                ]);

                foreach ($validatedData['commandes'] as $commandeData) {
                    $id_plat = $commandeData['id_plat'];
                    $quantite_plat = $commandeData['quantite'];

                    if ($quantite_plat > 0) {
                        $plat = Plat::findOrFail($id_plat);
                        $commande->details()->create([
                            'id_plat' => $id_plat,
                            'quantite' => $quantite_plat,
                            'prix_unitaire' => $plat->prix,
                            'prix_total' => $plat->prix * $quantite_plat,
                        ]);
                    }
                }

                foreach ($produitsQuantites as $id_produit => $quantite) {
                    $produit = Produit::findOrFail($id_produit);
                    $produit->updateStock($quantite);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Commande créée avec succès.',
                'commande_id' => $commande->id_commande,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la création de la commande : ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update($id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return redirect()->back()->with('error', 'Commande non trouvée.');
        }

        $commande->status = 'terminé';
        $commande->save();

        return redirect()->route('user.commande', $commande->id)->with('success', 'Le statut de la commande a été mis à jour avec succès');
    }

    public function generateInvoice($id)
    {
        // Récupérer la commande avec ses détails
        $commande = Commande::with('details')->findOrFail($id);

        // Calculer le total
        $total = $commande->details->sum('prix_total');

        // Récupérer le nom de l'utilisateur connecté
        $utilisateur = auth()->user()->name;

        // Chemin absolu vers le logo
        $logoPath = public_path('assets/img/logo.png');

        // Contenu HTML pour le PDF
        $html = '
    <div style="font-family: Arial, sans-serif; color: #444;">
        <!-- En-tête -->
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #007BFF; padding-bottom: 10px; margin-bottom: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 24px; color: #007BFF;">Facture</h1>
                <p style="margin: 0; font-size: 14px; color: #777;">Commande #: ' . $commande->id_commande . '</p>
                <p style="margin: 0; font-size: 14px; color: #777;">Date : ' . $commande->created_at->format('d/m/Y') . '</p>
            </div>
            <img src="data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) . '"
                 alt="Logo" style="width: 70px; height: auto;">
        </div>

        <!-- Détails de la commande -->
        <h3 style="color: #007BFF; border-bottom: 2px solid #007BFF; padding-bottom: 5px;">Détails de la Commande</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;">
            <thead>
                <tr style="background-color: #007BFF; color: #fff; text-align: left;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Nom du Plat</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Quantité</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Prix Unitaire</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Prix Total</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($commande->details as $detail) {
            $html .= '<tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">' . $detail->plat->nom . '</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">' . $detail->quantite . '</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">' . $detail->prix_unitaire . ' FCFA</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">' . $detail->prix_total . ' FCFA</td>
                  </tr>';
        }

        $html .= '</tbody>
        </table>

        <!-- Total -->
        <div style="margin-top: 20px; text-align: right;">
            <h4 style="color: #007BFF;">Total : ' . $total . ' FCFA</h4>
        </div>

        <!-- commande crée par: effectué -->
        <p style="margin-top: 20px; font-size: 14px; text-align: left;">Paiement effectué par : <strong>' . $utilisateur . '</strong></p>

        <!-- Note -->
        <p style="margin-top: 30px; font-size: 12px; color: #777; text-align: center;">Merci pour votre confiance !</p>
    </div>';

        try {
            // Charger le HTML dans DomPDF
            $pdf = Pdf::loadHTML($html);

            // Télécharger le PDF
            return $pdf->download('facture_' . $commande->id_commande . '.pdf');
        } catch (\Exception $e) {
            // Gestion des erreurs en cas de problème avec DomPDF
            return response()->json([
                'message' => 'Une erreur est survenue lors de la génération de la facture.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}


