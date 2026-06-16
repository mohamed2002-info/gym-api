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

// ---- Routes publiques (authentification) ----
Route::post('/register', [AuthController::class, 'register']);
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

    // Membres (CRUD complet) + renouvellement — accessible à tout utilisateur connecté
    Route::apiResource('members', MemberController::class);
    Route::post('/members/{member}/renew', [MemberController::class, 'renew']);

    // Types d'abonnement : lecture pour tous les utilisateurs connectés
    Route::get('subscription-types', [SubscriptionTypeController::class, 'index']);
    Route::get('subscription-types/{subscription_type}', [SubscriptionTypeController::class, 'show']);

    // Types d'abonnement : création / modification / suppression réservées à l'ADMIN
    Route::middleware('admin')->group(function () {
        Route::post('subscription-types', [SubscriptionTypeController::class, 'store']);
        Route::put('subscription-types/{subscription_type}', [SubscriptionTypeController::class, 'update']);
        Route::delete('subscription-types/{subscription_type}', [SubscriptionTypeController::class, 'destroy']);
    });
});
