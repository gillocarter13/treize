<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Produit;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        // Récupérer tous les produits avec les fournisseurs associés
        $produits = Produit::with('fournisseur')->paginate(5);  // Relation fournisseur déjà définie
        $fournisseurs = Fournisseur::all();

        // Vérifier si des produits sont en-dessous du seuil d'alerte
        $produits_en_alerte = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        return view('admin.create_produit', compact('produits', 'fournisseurs', 'produits_en_alerte'));
    }
    public function destroy($id_produit)
    {
        // Supprimer le produit par son id
        Produit::where('id_produit', $id_produit)->delete();

        // Rediriger avec un message de succès
        return redirect()->route('admin.create_produit')->with('success', 'Produit supprimé avec succès');
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'type' => 'required|string',
                'unité_de_mesure' => 'required|string|max:255',
                'id_fournisseur' => 'required|exists:fournisseur,id_fournisseur',
                'quantité' => 'required|integer|min:1',
                'prix_unitaire' => 'required|numeric|min:0',
                'seuil_alerte' => 'nullable|integer',
            ]);

            $prixTotal = $validatedData['quantité'] * $validatedData['prix_unitaire'];

            Produit::create([
                'nom' => $validatedData['nom'],
                'type' => $validatedData['type'],
                'unité_de_mesure' => $validatedData['unité_de_mesure'],
                'id_fournisseur' => $validatedData['id_fournisseur'],
                'quantité' => $validatedData['quantité'],
                'quantite_stock' => $validatedData['quantité'],
                'prix_unitaire' => $validatedData['prix_unitaire'],
                'prix_total' => $prixTotal,
                'seuil_alerte' => $validatedData['seuil_alerte'],
            ]);

            return redirect()->back()->with('success', 'Produit ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du produit : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_produit)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'unité_de_mesure' => 'required|string|max:255',
            'id_fournisseur' => 'required|exists:fournisseur,id_fournisseur',
            'quantité' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'seuil_alerte' => 'nullable|integer|min:0',
        ]);

        $produit = Produit::findOrFail($id_produit);
        $prixTotal = $validatedData['quantité'] * $validatedData['prix_unitaire'];

        $produit->update([
            'nom' => $validatedData['nom'],
            'type' => $validatedData['type'],
            'unité_de_mesure' => $validatedData['unité_de_mesure'],
            'id_fournisseur' => $validatedData['id_fournisseur'],
            'quantité' => $validatedData['quantité'],
            'quantite_stock' => $validatedData['quantité'],
            'prix_unitaire' => $validatedData['prix_unitaire'],
            'prix_total' => $prixTotal,
            'seuil_alerte' => $validatedData['seuil_alerte'],
        ]);

        if ($produit->quantite_stock <= $produit->seuil_alerte) {
            return redirect()->back()->withErrors(["Attention ! Le stock pour {$produit->nom} est en dessous du seuil critique."]);
        }

        return redirect()->route('admin.create_produit')->with('success', 'Produit mis à jour avec succès.');
    }


    public function totalAchats(Request $request)
    {
        $filtre = $request->input('filtre', 'aujourdhui');

        // Utilisez Carbon pour les filtres de date
        if ($filtre == 'aujourdhui') {
            $date = Carbon::today();
            $totalAchats = Produit::whereDate('created_at', $date)->sum('prix_total');
        } elseif ($filtre == 'mois') {
            $date = Carbon::now()->startOfMonth();
            $totalAchats = Produit::where('created_at', '>=', $date)->sum('prix_total');
        } elseif ($filtre == 'annee') {
            $date = Carbon::now()->startOfYear();
            $totalAchats = Produit::where('created_at', '>=', $date)->sum('prix_total');
        } else {
            // Par défaut, afficher le total de tous les achats
            $totalAchats = Produit::sum('prix_total');
        }

        // Passez ce total à la vue
        return view('admin.dashboard', compact('totalAchats', 'filtre'));
    }
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id_fournisseur'); // Assurez-vous que les clés étrangères sont correctes
    }
}
