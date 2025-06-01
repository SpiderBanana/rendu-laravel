<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ApiKeyController extends Controller
{
    /**
     * Affiche la liste des clés API de l'utilisateur connecté.
     */
    public function index()
    {
        $user = Auth::user();
        $keys = $user->apiKeys()
                     ->orderBy('created_at', 'desc')
                     ->get(['id', 'uuid', 'name', 'key', 'created_at']);

        return Inertia::render('Dashboard/ApiKeys/Index', [
            'apiKeys' => $keys,
        ]);
    }

    /**
     * Crée une nouvelle clé API pour l'utilisateur connecté.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->apiKeys()->create([
            'name' => $request->name,
            // uuid et key sont générés automatiquement dans le modèle
        ]);

        return redirect()->route('api-keys.index')
                         ->with('success', 'Nouvelle clé API générée.');
    }

    /**
     * Supprime une clé API existante (vérification que la clé appartient bien à l'utilisateur).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $apiKey = ApiKey::where('id', $id)
                        ->where('user_id', $user->id)
                        ->firstOrFail();

        $apiKey->delete();

        return redirect()->route('api-keys.index')
                         ->with('success', 'Clé API supprimée.');
    }
}
