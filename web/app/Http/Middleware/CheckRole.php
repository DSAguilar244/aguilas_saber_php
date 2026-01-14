<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar rol y/o permiso antes de acceder a una ruta.
 */
class CheckRole
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param string|null $role       Rol requerido (opcional)
     * @param string|null $permission Permiso requerido (opcional)
     */
    public function handle(Request $request, Closure $next, ?string $role = null, ?string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        // Admin siempre permite
        if ($user->roles->contains('name', 'admin')) {
            return $next($request);
        }

        // Si se solicitó solo rol y no es admin, denegar
        if ($role && !$permission) {
            return $this->deny($request);
        }

        // Si se solicitó permiso, validar
        if ($permission && !$user->hasPermissionTo($permission)) {
            return $this->deny($request);
        }

        return $next($request);
    }

    private function deny(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No tienes permiso para acceder a este recurso'], 403);
        }

        return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
    }
}
