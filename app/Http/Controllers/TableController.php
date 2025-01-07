<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::paginate(20);
        return view('admin.create_table', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_table' => 'required|string|max:255',
            'capacité' => 'required|integer',
        ]);

        Table::create($request->all());

        return redirect()->route('admin.table')->with('success', 'Table ajoutée avec succès.');
    }

    public function edit(Table $table)
    {
        return view('create_table', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'nom_table' => 'required|string|max:255',
            'capacité' => 'required|integer',
        ]);

        $table->update($request->all());

        return redirect()->route('admin.table')->with('success', 'Table mise à jour avec succès.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('admin.table')->with('success', 'Table supprimée avec succès.');
    }
}
