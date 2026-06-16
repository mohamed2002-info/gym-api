<?php

use Illuminate\Support\Facades\Route;

// Page racine : petit JSON de statut (pas de vue Blade → pas d'erreur 500).
Route::get('/', function () {
    return response()->json([
        'app'     => 'GymManager API',
        'status'  => 'ok',
        'docs'    => 'Les endpoints sont sous /api (ex: /api/login).',
    ]);
});
