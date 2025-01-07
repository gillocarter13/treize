<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $role = $user->role;
        $profileImage = $user->profile_image
            ? asset('storage/' . $user->profile_image)
            : asset('assets/img/default-profile.png'); // Chemin vers l'image par défaut

        return view('layouts.profile', compact('user','profileImage', 'role'));
    }
    public function shows()
    {
        $user = Auth::user();
        $role = $user->role;
        $profileImage = $user->profile_image
            ? asset('storage/' . $user->profile_image)
            : asset('assets/img/default-profile.png'); // Chemin vers l'image par défaut

        return view('layouts.profiles', compact('user', 'profileImage', 'role'));
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/profile_images', $filename);

            // Supprimer l'ancienne image si elle existe
            if ($user->profile_image && Storage::exists('public/profile_images/' . $user->profile_image)) {
                Storage::delete('public/profile_images/' . $user->profile_image);
            }

            // Mettre à jour l'image de profil dans la base de données
            $user->profile_image = 'profile_images/' . $filename;
            $user->save();
        }

        // Retourner avec un message de succès
        return redirect()->back()->with('success', 'Image de profil mise à jour avec succès.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        // Vérifier si le mot de passe actuel est correct
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
 }

}
