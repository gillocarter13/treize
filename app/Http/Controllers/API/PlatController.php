<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plat;

class PlatController extends Controller
{
    public function index()
    {
        // Récupérer tous les plats
        $plats = Plat::all();

        // Retourner la réponse en JSON avec tous les éléments du plat
        return response()->json([
            'status' => 'success',
            'data' => $plats,
        ], 200);
    }
}
