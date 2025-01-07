<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ChangePasswordsController extends Controller
{
  /**
   * API pour changer le mot de passe de l'utilisateur.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function changePassword(Request $request)
  {
    // Validation des champs
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|confirmed|min:8',
    ]);

    $user = Auth::user();

    // Vérification du mot de passe actuel
    if (!Hash::check($request->input('current_password'), $user->password)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Le mot de passe actuel est incorrect.',
      ], 400); // Bad Request
    }

    // Mise à jour du mot de passe
    $user->password = Hash::make($request->input('new_password'));
    $user->save();

    return response()->json([
      'status' => 'success',
      'message' => 'Mot de passe mis à jour avec succès.',
    ], 200); // Success
  }
}
