<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ApiKeyController; // <— ne pas oublier d’importer

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Page d’accueil du dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // CRUD des clés API
    Route::get('/dashboard/api-keys', [ApiKeyController::class, 'index'])
         ->name('api-keys.index');

    Route::post('/dashboard/api-keys', [ApiKeyController::class, 'store'])
         ->name('api-keys.store');

    Route::delete('/dashboard/api-keys/{id}', [ApiKeyController::class, 'destroy'])
         ->name('api-keys.destroy');
});
