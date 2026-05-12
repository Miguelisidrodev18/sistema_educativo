<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // Permitir acceso a la ruta de cambio de contraseña y logout
            if ($request->routeIs('password.change', 'password.change.update', 'logout')) {
                return $next($request);
            }

            return redirect()->route('password.change')
                ->with('info', 'Por seguridad, debes cambiar tu contraseña antes de continuar.');
        }

        return $next($request);
    }
}
