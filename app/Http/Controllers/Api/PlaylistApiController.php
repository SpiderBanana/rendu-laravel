<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;

class PlaylistApiController extends Controller
{
    /**
     * Renvoie la liste des playlists de l'utilisateur authentifié (via API Key).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Récupère toutes les playlists de cet utilisateur
        // Vous pouvez ajouter ->with('tracks') si vous souhaitez inclure les morceaux associés
        $playlists = Playlist::where('user_id', $user->id)
                              ->with('tracks') // facultatif
                              ->get();

        return response()->json([
            'data' => $playlists
        ], 200);
    }
}
