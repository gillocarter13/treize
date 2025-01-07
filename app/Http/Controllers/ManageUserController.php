<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le terme de recherche depuis la requête
        $search = $request->input('search');

        // Récupérer les utilisateurs avec pagination et option de recherche
        $users = User::with('role')
            ->where('name', 'like', '%' . $search . '%') // Filtrer par nom
            ->orWhere('email', 'like', '%' . $search . '%') // Filtrer par email
            ->orWhereHas('role', function ($query) use ($search) {
                $query->where('nom', 'like', '%' . $search . '%'); // Filtrer par rôle
            })
            ->orderBy('id_user', 'asc') // Trier par ID croissant (du plus ancien au plus récent)
            ->paginate(5);

        return view('admin.user_management', compact('users', 'search'));
    }



    public function edit($id_user)
    {
        $user = User::with('role')->where('id_user', $id_user)->firstOrFail();
        return view('admin.edit_user', compact('user'));
    }

        public function update(Request $request, $id)
        {
            // Validation des données
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id . ',id_user',
                'id_role' => 'required|exists:roles,id_role',
                'numero' => 'required|numeric',
            ]);

            // Mettre à jour les informations de l'utilisateur
            $user = User::findOrFail($id);
            $user->update($request->only(['name', 'email', 'id_role', 'numero']));

            return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
        }



    public function destroy($id_user)
    {
        User::where('id_user', $id_user)->delete();
        return redirect()->route('admin.users')->with('error', 'Utilisateur supprimé avec succès');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.create_user', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_role' => 'required|exists:roles,id_role',
            'numero' => 'required|string|max:15',
            'adresse' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'numero' => $request->numero,
            'adresse' => $request->adresse,
        ]);
        Auth::login($user);
        // Autres actions si nécessaire

        return redirect()->route('admin.users')->with('success', 'Utilisateur creer avec succès');
    }

}
