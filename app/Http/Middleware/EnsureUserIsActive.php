<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia; // Asegúrate de importar esto
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Si no hay usuario autenticado (aunque auth ya debería haberlo filtrado)
        // 2. O si el status no es ACTIVE
        if (!$request->user() || $request->user()->status !== 'ACTIVE') {
            
            // Usamos Inertia::render y le agregamos ->toResponse($request)
            // Esto soluciona el error de tipado (Expected Response, found Inertia\Response)
            return Inertia::render('auth/WaitApproval', [
                'status' => $request->user()->status
            ])->toResponse($request);
        }

        return $next($request);
    }
}