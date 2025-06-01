<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Récupère la clé envoyée dans le header "x-api-key"
        $providedKey = $request->header('x-api-key');

        if (!$providedKey) {
            return response()->json([
                'message' => 'Clé API manquante.'
            ], 401);
        }

        // Vérifie que la clé existe en base
        $apiKey = ApiKey::where('key', $providedKey)->first();

        if (!$apiKey) {
            return response()->json([
                'message' => 'Clé API invalide.'
            ], 401);
        }

        // Authentifie l’utilisateur pour cette seule requête (sans session)
        Auth::onceUsingId($apiKey->user_id);

        return $next($request);
    }
}
