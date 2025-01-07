<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;

// class AuthController extends Controller
// {
//     public function login(Request $request)
//     {
//         // Valider les champs
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         // Vérification des identifiants
//         if (Auth::attempt($request->only('email', 'password'))) {
//             $user = Auth::user();

//             // Générer un token Sanctum
//             $token = $user->createToken('authToken')->plainTextToken;

//             return response()->json([
//                 'message' => 'Login successful',
//                 'user' => [
//                     'id' => $user->id_user,
//                     'name' => $user->name,
//                     'email' => $user->email,
//                 ],
//                 'token' => $token,
//             ], 200);
//         }

//         return response()->json([
//             'message' => 'Invalid email or password',
//         ], 401);
//     }
// }
