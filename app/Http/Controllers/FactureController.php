<?php

namespace App\Http\Controllers;

use App\Models\Commande; // Assurez-vous d'importer votre modèle Commande
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class FactureController extends Controller
{
    public function generateInvoice(Request $request, $id)
    {
        // Récupérer les détails de la commande par ID
        $commande = Commande::findOrFail($id);

        // Générer la facture PDF
        $pdf = PDF::loadView('invoices.invoice', compact('commande'));

        // Vous pouvez soit télécharger ou afficher le PDF
        return $pdf->download('facture-' . $commande->id_commande . '.pdf'); // Pour télécharger
        // return $pdf->stream('facture-' . $commande->id_commande . '.pdf'); // Pour afficher
    }
}
