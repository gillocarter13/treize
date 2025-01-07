<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailsCommandePersonnalisee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CommandePersonnaliseeController extends Controller
{
    // Méthode pour afficher toutes les commandes personnalisées
    public function index()
    {
        // Récupérer les produits de type 'aliment' et les organiser par id
        $produits_aliment = Produit::where('type', 'aliment')->get()->keyBy('id_produit');

        // Récupérer les commandes personnalisées du jour
        $commandes = Commande::where('type', 'personalisee')
        ->whereDate('created_at', Carbon::today()) // Filtrer par date du jour
            ->with(['details_personnalises.produit']) // Précharger les produits
            ->orderByRaw("CASE WHEN status = 'terminee' THEN 0 ELSE 1 END") // Mettre 'terminee' en haut
            ->orderByRaw("CASE WHEN status = 'terminee' THEN created_at END ASC") // Trier les commandes terminées par date croissante
            ->orderByRaw("CASE WHEN status != 'terminee' THEN created_at END DESC") // Trier les commandes en attente du plus récent au moins récent
            ->get();

        // Récupérer les produits en alerte
        $produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        // Retourner la vue avec les données nécessaires
        return view('user.commande_p', compact('produits_aliment', 'commandes', 'produits_en_alerte'));
    }


    // Méthode pour créer une nouvelle commande personnalisée
    // Méthode pour créer une nouvelle commande personnalisée
    public function store(Request $request)
    {
        $request->validate(
            [
                'prix_unitaire' => 'required|numeric|min:0',
                'quantite_plat' => 'required|integer|min:1', // Nombre de plats commandés
                'produits' => 'required|array',
                'produits.*' => 'exists:produits,id_produit',
                'quantites' => 'required|array',
                'quantites.*' => 'numeric|min:0.01',
            ]
        );

        // Démarrer une transaction pour assurer la cohérence des données
        DB::beginTransaction();

        try {
            // Créer la commande personnalisée
            $commande = Commande::create([
                'status' => 'en attente',
                'type' => 'personalisee',
            ]);

            $produitsQuantites = [];

            foreach ($request->produits as $id_produit) {
                if (isset($request->quantites[$id_produit])) {
                    // Quantité de produit pour un plat
                    $quantiteParPlat = $request->quantites[$id_produit];

                    // Multiplier par le nombre de plats commandés
                    $quantiteTotale = $quantiteParPlat * $request->quantite_plat;

                    $produitsQuantites[] = [
                        'id_produit' => $id_produit,
                        'quantite' => $quantiteTotale, // Quantité totale de produits
                    ];

                    // Vérifier que la quantité en stock est suffisante
                    $produit = Produit::findOrFail($id_produit);
                    if ($produit->quantite_stock < $quantiteTotale) {
                        // Renvoyer un message d'erreur en cas de stock insuffisant
                        return redirect()->back()->with('error', "Stock insuffisant pour le produit : {$produit->nom}");
                    }

                    // Soustraire la quantité totale du stock
                    $produit->quantite_stock -= $quantiteTotale;
                    $produit->save();
                } else {
                    return redirect()->back()->with('error', "Quantité manquante pour le produit : $id_produit");
                }
            }

            // Calculer le prix total
            $prixTotal = $request->quantite_plat * $request->prix_unitaire;

            // Créer l'enregistrement dans DetailsCommandePersonnalisee
            DetailsCommandePersonnalisee::create([
                'id_commande' => $commande->id_commande,
                'produits_quantites' => json_encode($produitsQuantites),
                'quantite_plat' => $request->quantite_plat,
                'prix_unitaire' => $request->prix_unitaire,
                'prix_total' => $prixTotal,
                'type' => 'personalisee',
            ]);

            // Tout s'est bien passé, on valide la transaction
            DB::commit();

            return redirect()->route('user.commande_p')->with('success',
                'Plat personnalisé créé avec succès.'
            );
        } catch (\Exception $e) {
            // En cas d'erreur, on annule toutes les opérations
            DB::rollBack();

            return redirect()->back()->with('error', 'Erreur lors de la création de la commande.');
        }
    }

    public function update($id)
    {
        // Trouvez la commande par ID
        $commande = Commande::find($id);

        // Vérifiez si la commande existe
        if (!$commande) {
            return redirect()->back()->with('error', 'Commande non trouvée.');
        }

        // Mettez à jour le statut de la commande
        $commande->status = 'terminé'; // Mettez à jour avec le statut souhaité
        $commande->save();

        return redirect()->back()->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }
    public function generateInvoice($id)
    {
        // Récupérer la commande personnalisée avec ses détails
        $commande = Commande::with('details_personnalises.produit')->findOrFail($id);

        // Calculer le total
        $total = $commande->details_personnalises->sum('prix_total');

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
                <h1 style="margin: 0; font-size: 24px; color: #007BFF;">Facture Personnalisée</h1>
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
                    <th style="padding: 10px; border: 1px solid #ddd;">Quantité</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Prix Unitaire</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Prix Total</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($commande->details_personnalises as $detail) {
            $html .= '<tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">' . $detail->quantite_plat . '</td>
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

        <!-- Paiement effectué -->
        <p style="margin-top: 20px; font-size: 14px; text-align: left;">Paiement effectué par : <strong>' . $utilisateur . '</strong></p>

        <!-- Note -->
        <p style="margin-top: 30px; font-size: 12px; color: #777; text-align: center;">Merci pour votre confiance !</p>
    </div>';

        try {
            // Charger le HTML dans DomPDF
            $pdf = Pdf::loadHTML($html);

            // Télécharger le PDF
            return $pdf->download('facture_personnalisee_' . $commande->id_commande . '.pdf');
        } catch (\Exception $e) {
            // Gestion des erreurs en cas de problème avec DomPDF
            return response()->json([
                'message' => 'Une erreur est survenue lors de la génération de la facture.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
