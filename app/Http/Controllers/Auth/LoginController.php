<?php
// LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Vérifie si l'utilisateur est authentifié
        if (Auth::check()) {
            // Déconnecte l'utilisateur s'il est déjà connecté
            Auth::logout();
            // Invalide la session en cours
            session()->invalidate();
            session()->regenerateToken();
        }

        // Retourne la vue de login
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Valider les données du formulaire de connexion
        $credentials = $request->only('email', 'password');

        // Vérifier les identifiants et tenter la connexion
        if (Auth::attempt($credentials)) {
            // Récupérer l'utilisateur authentifié
            $utilisateur = Auth::user();

            // Charger le rôle de l'utilisateur en utilisant la relation avec la table `role`
            $role = $utilisateur->role; // Relation bien définie

            // Rediriger en fonction du rôle
            if ($role && $role->id_role == 1) { // 1 correspond à admin
                return redirect()->route('admin.home')->with('alert', [
                    'type' => 'success',
                    'message' => 'Bienvenue, ' . $utilisateur->name . ' ! Vous êtes maintenant connecté en tant qu\'administrateur.'
                ]);
            } elseif ($role && $role->id_role == 2) { // 2 correspond à employé
                return redirect()->route('user.home')->with('alert', [
                    'type' => 'success',
                    'message' => 'Bienvenue, ' . $utilisateur->name . ' ! Vous êtes maintenant connecté en tant qu\'employé.'
                ]);
            }
        }

        return redirect()->back()->with('error', 'Les informations de connexion sont incorrectes.');

    }

    // Gère la déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalider la session de l'utilisateur et régénérer le token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Rediriger vers la page de connexion après déconnexion
        return redirect()->route('login');
    }
}
