<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\SubscriptionTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes API — Application Salle de Sport
|--------------------------------------------------------------------------
*/

// ---- Route publique : connexion uniquement ----
// (Pas d'inscription publique : un seul compte admin créé par le seeder.)
Route::post('/login', [AuthController::class, 'login']);

// ---- Routes protégées par token Sanctum ----
Route::middleware('auth:sanctum')->group(function () {

    // Auth / Profil
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthController::class, 'changePassword']);

    // Tableau de bord
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Membres (CRUD complet) + renouvellement
    Route::apiResource('members', MemberController::class);
    Route::post('/members/{member}/renew', [MemberController::class, 'renew']);

    // Types d'abonnement (CRUD complet)
    Route::apiResource('subscription-types', SubscriptionTypeController::class);
});
