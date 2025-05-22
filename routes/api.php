<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Bungalow;
use App\Models\TableRepas;
use App\Models\Kayak;
use App\Models\Activite;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes authentifiées
Route::middleware('auth:sanctum')->group(function () {
    // Informations utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Routes administratives pour les réservations (nécessitent authentification)
    Route::apiResource('reservations', \App\Http\Controllers\ReservationController::class);
    
    // Route pour mettre à jour le numéro de réservation formaté (CH25050003)
    Route::post('/update-reservation-number/{id}', [\App\Http\Controllers\ReservationController::class, 'updateReservationNumber']);
    
    // Routes pour les clients
    Route::apiResource('clients', \App\Http\Controllers\ClientController::class);
});

// Routes publiques avec limitation de débit et validation
Route::middleware(['throttle.api'])->group(function () {
    
    // Routes simples pour réservation bungalow - accessibles sans authentification pour le frontend
    Route::post('/bungalow-reservation', [\App\Http\Controllers\ReservationController::class, 'storeBungalowReservation']);
    
    Route::get('/bungalow-availability', [\App\Http\Controllers\ReservationController::class, 'getBungalowAvailability']);
    
    // Routes pour récupérer les données nécessaires aux formulaires de réservation avec validation
    Route::get('/bungalows', function () {
        try {
            return Bungalow::where('disponible', true)->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des bungalows'], 500);
        }
    });
    
    Route::get('/tables-repas', function () {
        try {
            return TableRepas::where('disponible', true)->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des tables'], 500);
        }
    });
    
    Route::get('/kayaks', function () {
        try {
            return Kayak::where('disponible', true)->get();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des kayaks'], 500);
        }
    });
    
    Route::get('/activites', function () {
        try {
            return Activite::all();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des activités'], 500);
        }
    });
});
