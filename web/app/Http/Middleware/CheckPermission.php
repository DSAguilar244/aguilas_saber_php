<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        // Admin siempre tiene acceso
        if ($user && ($user->roles->contains('name', 'admin'))) {
            return $next($request);
        }

        // Verificar si el usuario tiene el permiso
        if ($user && $user->hasPermissionTo($permission)) {
            return $next($request);
        }

        // Si no tiene permiso, redirigir con error
        return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
    }
}
