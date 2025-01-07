<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\Validator;

class FournisseurController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $fournisseurs = Fournisseur::when($search, function ($query, $search) {
            return $query->where('nom', 'like', '%' . $search . '%')
                ->orWhere('contact', 'like', '%' . $search . '%')
                ->orWhere('adresse', 'like', '%' . $search . '%');
        })->paginate(5);

        return view('admin.create_fournisseur', compact('fournisseurs', 'search'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|unique:fournisseur',
            'contact' => 'required|numeric|unique:fournisseur',
            'adresse' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Fournisseur::create([
            'nom' => $request->nom,
            'contact' => $request->contact,
            'adresse' => $request->adresse,
        ]);

        return redirect()->back()->with('success', 'Votre fournisseur a été ajouté avec succès.');
    }

    public function edit($id_fournisseur)
    {
        $fournisseur = Fournisseur::findOrFail($id_fournisseur);
        return view('admin.edit_fournisseur', compact('fournisseur'));
    }

    public function update(Request $request, $id_fournisseur)
    {
        $request->validate([
            'nom' => 'required|string|unique:fournisseur,nom,' . $id_fournisseur . ',id_fournisseur',
            'contact' => 'required|numeric',
            'adresse' => 'required|string|max:255',
        ]);

        $fournisseur = Fournisseur::findOrFail($id_fournisseur);
        $fournisseur->update($request->only(['nom', 'contact', 'adresse']));

        return redirect()->route('admin.create_fournisseur')->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy($id_fournisseur)
    {
        Fournisseur::where('id_fournisseur', $id_fournisseur)->delete();
        return redirect()->route('admin.create_fournisseur')->with('success', 'Fournisseur supprimé avec succès.');
    }
}
