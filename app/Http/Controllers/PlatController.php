<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;
use App\Models\Produit;

class PlatController extends Controller
{
    // Afficher la page d'ajout de plat


    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255|unique:plat',
            'description' => 'required|string',
            'prix' => 'nullable|numeric|min:0',
            'produits' => 'required|array',
            'produits.*' => 'exists:produits,id_produit',
            'quantites' => 'required|array',
            'quantites.*' => 'numeric|min:0.01', // Assurez-vous que la quantité est un nombre positif
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        // Préparer les données des produits et quantités
        $produitsQuantites = [];
        foreach ($request->produits as $id_produit) {
            if (isset($request->quantites[$id_produit])) {
                $quantite = $request->quantites[$id_produit];
                $produitsQuantites[] = [
                    'id_produit' => $id_produit,
                    'quantite' => $quantite,
                ];
            }
        }

        // Vérifier si les produits et quantités sont correctement définis
        if (empty($produitsQuantites)) {
            return redirect()->back()->withErrors('Aucun produit valide avec une quantité définie n\'a été fourni.');
        }

        // Gérer l'upload de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('public/plat_images', $filename); // Enregistrer dans le dossier plat_images
        }

        // Créer le plat avec les produits, quantités et image
        $plat = Plat::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix, // Optionnel
            'produits_quantites' => json_encode($produitsQuantites), // Encodez les quantités en JSON
            'image' => $imagePath ? 'plat_images/' . basename($imagePath) : null, // Enregistrer l'image si elle existe
        ]);

        // Si une image a été téléchargée, mettre à jour le plat avec l'image
        if ($imagePath) {
            $plat->image = 'plat_images/' . basename($imagePath);
            $plat->save(); // Sauvegarder le plat avec le chemin de l'image
        }

        return redirect()->back()->with('success', 'Plat créé avec succès !');
    }


    public function index()
    {
        // Récupérer tous les plats
        $plats = Plat::all();
        $produits_aliment = Produit::where('type', 'aliment')->get()->keyBy('id_produit');


        return view('admin.created_plat', compact('produits_aliment', 'plats'));
        // Retourner la vue 'plats.index' avec les plats récupérés
    }
    public function edit($id)
    {
        // Récupérer le plat à éditer
        $plat = Plat::findOrFail($id);
        // Récupérer les produits de type 'aliment'
        $produits_aliment = Produit::where('type', 'aliment')->get()->keyBy('id_produit');

        // Retourner la vue d'édition avec les données du plat
        return view('admin.created_plat', compact('plat', 'produits_aliment'));
    }
    public function activer($id)
    {
        $plat = Plat::findOrFail($id);
        $plat->update(['is_active' => true]);

        return redirect()->route('admin.create_plat')->with('success', 'Le plat a été activé avec succès.');
    }

    public function desactiver($id)
    {
        $plat = Plat::findOrFail($id);
        $plat->update(['is_active' => false]);

        return redirect()->route('admin.create_plat')->with('success', 'Le plat a été désactivé avec succès.');
    }


    public function destroy($id_plat)
    {
        // Récupérer et supprimer le plat
        $plat = Plat::findOrFail($id_plat);
        $plat->delete();

        return redirect()->back()->with('success', 'Plat supprimé avec succès !');
    }

}
