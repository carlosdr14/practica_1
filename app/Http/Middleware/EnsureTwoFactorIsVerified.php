<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorIsVerified
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario tiene una sesión de doble factor activa
        if (session('auth_user_id') && !auth()->check()) {
            return redirect()->route('authentication')->withErrors([
                'error' => 'You need to complete the two-factor authentication process first.',
            ]);
        }

        return $next($request);
    }
}