<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et si son rôle est 'admin'
        if (auth()->check() && auth()->user()->role && auth()->user()->role->id_role == 1) {
            return $next($request); // Continuer vers la prochaine requête
        }

        // Rediriger vers une page d'accès refusé si l'utilisateur n'est pas admin
        return redirect('/home')->with('alert', [
            'type' => 'danger',
            'message' => 'Accès refusé. Vous n\'êtes pas autorisé à accéder à cette page.'
        ]);
    }
}
