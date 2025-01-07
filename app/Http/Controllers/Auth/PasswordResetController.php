<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ResetPasswordNotification;

class PasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.password-reset-request');
    }

    public function sendResetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Aucun utilisateur trouvé avec cet email.');
        }

        // Générer un mot de passe temporaire
        $temporaryPassword = Str::random(10);

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        // Envoyer la notification par email
        $user->notify(new ResetPasswordNotification($temporaryPassword));

        return back()->with('success', 'Un mot de passe temporaire vous a été envoyé par email.');
    }
}
