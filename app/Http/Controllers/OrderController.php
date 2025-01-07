<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Affiche toutes les commandes
   public function showOrders()
    {
        $commandes = Commande::with('details')->get(); // Récupérez toutes les commandes avec leurs détails
        return view('commandes', compact('commandes')); // Affichez la vue des commandes
    }

    // Affiche une commande spécifique
    public function showOrder($id)
    {
        $commande = Commande::with('details')->findOrFail($id); // Récupérez une commande spécifique
        return view('commande.show', compact('commande')); // Affichez les détails de la commande
    }

    // Met à jour le statut d'une commande
    public function updateStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id); // Récupérez la commande
        $commande->status = $request->status; // Mettez à jour le statut
        $commande->save(); // Enregistrez les changements

        return redirect()->route('orders.show')->with('success', 'Statut de la commande mis à jour avec succès.');
    }
}
