<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Vérifiez si l'utilisateur est connecté
        if (auth()->check()) {
            // Vérifiez le rôle de l'utilisateur
            if (auth()->user()->role && auth()->user()->role->name == $role) {
                return $next($request);
            }
        }

        // Redirige vers /login si l'utilisateur n'a pas le rôle attendu
        return redirect('/login');
    }
}
