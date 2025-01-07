<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // Fonction de connexion
    public function login(Request $request)
    {
        // Validation des informations de connexion
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Tentative de connexion
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Si les identifiants sont valides, récupérer l'utilisateur
            $user = Auth::user();
            $roleId = $user->id_role; // Récupérer l'id_role de l'utilisateur

            // Créer un token pour l'utilisateur
            $token = $user->createToken('YourApp')->plainTextToken;

            // Vérifier si l'utilisateur a le rôle id_role 2 (employé) pour lui permettre d'accéder à la page d'accueil
            if ($roleId == 2) {
                // Redirection vers la page d'accueil de l'utilisateur
                $redirectTo = '/home';
            } else {
                // Si l'utilisateur n'a pas le bon rôle, ne pas lui permettre d'accéder à la page d'accueil
                return response()->json([
                    'message' => 'Seul les Serveurs ont droit a cette page .'
                ], 403);
            }

            // Retourner la réponse avec l'utilisateur, le token, le role_id et l'URL de redirection
            return response()->json([
                'id_role' => $roleId,  // Ajout du role_id
                'message' => 'Login successful',
                'status' => 'ok',
                'user' => $user,
                'token' => $token,
                'redirect_to' => $redirectTo, // Ajout de l'URL de redirection
            ], 200);
        }

        // Si les identifiants sont invalides
        return response()->json(['message' => 'Identifiants ou mot de passe invalide'], 401);
    }

    // Fonction pour récupérer les informations de l'utilisateur
    public function getUserInfo(Request $request)
    {
        // Vérifier le token et retourner les données utilisateur
        $user = Auth::user();

        return response()->json([
            'status' => 'ok',
            'data' => [
                'id_user' => $user->id_user,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
            ],
        ]);
    }

    // Fonction de déconnexion
    public function logout(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Révoquer le token de l'utilisateur
        if ($user) {
            // Revoke le token de l'utilisateur
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Logout successful',
                'status' => 'ok',
            ], 200);
        }

        // Si l'utilisateur n'est pas authentifié
        return response()->json(['message' => 'User not authenticated'], 401);
    }
}
