<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les réservations
Route::apiResource('reservations', \App\Http\Controllers\ReservationController::class);

// Routes simples pour réservation bungalow
Route::post('/bungalow-reservation', [\App\Http\Controllers\ReservationController::class, 'storeBungalowReservation']);
Route::get('/bungalow-availability', [\App\Http\Controllers\ReservationController::class, 'getBungalowAvailability']);

// Routes pour récupérer les données nécessaires aux formulaires de réservation
Route::get('/bungalows', function () {
    return \App\Models\Bungalow::where('disponible', true)->get();
});

Route::get('/tables-repas', function () {
    return \App\Models\TableRepas::where('disponible', true)->get();
});

Route::get('/kayaks', function () {
    return \App\Models\Kayak::where('disponible', true)->get();
});

Route::get('/activites', function () {
    return \App\Models\Activite::all();
});

// Routes pour les clients
Route::apiResource('clients', \App\Http\Controllers\ClientController::class);
