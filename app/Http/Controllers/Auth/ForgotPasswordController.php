<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // Assurez-vous que cette vue existe
    }

    public function sendRandomPassword(Request $request)
    {
        // Validation de l'email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Récupérer l'utilisateur par l'email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'L\'adresse e-mail n\'est associée à aucun utilisateur.']);
        }

        // Générer un mot de passe aléatoire
        $newPassword = Str::random(8); // Mot de passe aléatoire de 8 caractères

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($newPassword);
        $user->save();

        // Essayer d'envoyer l'email avec gestion des erreurs
        try {
            Mail::to($user->email)->send(new PasswordResetMail($newPassword));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()]);
        }

        // Retourner une confirmation
        return redirect()->back()->with('status', 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.');
    }
}
