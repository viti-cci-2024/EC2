<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Bungalow;
use App\Models\Client;
use App\Models\Kayak;
use App\Models\Reservation;
use App\Models\TableRepas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Créer une réservation simple de bungalow (pas d'auth, juste infos formulaire)
     */
    public function storeBungalowReservation(Request $request)
    {
        Log::info('Requête de réservation reçue', $request->all());
        
        // Vérifier l'état de la connexion à la base de données
        try {
            $dbConnection = DB::connection()->getPdo();
            Log::info('Connexion à la base de données établie', [
                'database' => DB::connection()->getDatabaseName(),
                'host' => config('database.connections.mysql.host'),
                'connected' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de connexion à la base de données', [
                'error' => $e->getMessage(),
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database')
            ]);
            return response()->json(['message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()], 500);
        }
        
        try {
            $validated = $request->validate([
                'bungalow_id' => 'required|integer',
                'last_name' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'person_count' => 'required|integer|min:1',
            ]);
            
            Log::info('Données de réservation validées', $validated);
            
            // Vérifiez s'il y a des conflits de réservation en utilisant la table pivot
            $conflict = DB::table('reservations as r')
                ->join('reservation_bungalow as rb', 'r.id', '=', 'rb.reservation_id')
                ->where('rb.bungalow_id', $validated['bungalow_id'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('r.start_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhereBetween('r.end_date', [$validated['start_date'], $validated['end_date']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('r.start_date', '<=', $validated['start_date'])
                                ->where('r.end_date', '>=', $validated['end_date']);
                        });
                })->exists();
            
            if ($conflict) {
                Log::warning('Conflit de réservation détecté pour le bungalow', ['bungalow_id' => $validated['bungalow_id']]);
                return response()->json(['message' => 'Cette période n\'est pas disponible pour ce bungalow.'], 409);
            }
            
            Log::info('Aucun conflit de réservation détecté');
            
            // Test simple de la connexion à la base de données
            try {
                $testResult = DB::select('SELECT 1 as test');
                Log::info('Test de requête SQL réussi', ['result' => $testResult]);
            } catch (\Exception $e) {
                Log::error('Erreur lors du test SQL', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Erreur lors du test SQL: ' . $e->getMessage()], 500);
            }
            
            // Générer un numéro de réservation unique
            $reservationNumber = 'RES-' . strtoupper(substr($validated['last_name'], 0, 3)) . '-' . rand(1000, 9999);
            
            // Créer la réservation avec bungalow_id directement dans la table reservations
            // car la contrainte de clé étrangère l'exige
            $reservation = Reservation::create([
                'bungalow_id' => $validated['bungalow_id'],  // IMPORTANT: Ce champ est obligatoire
                'last_name' => $validated['last_name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'person_count' => $validated['person_count'],
                'numero' => $reservationNumber,
            ]);
            
            Log::info('Réservation créée avec bungalow_id', [
                'reservation_id' => $reservation->id,
                'bungalow_id' => $validated['bungalow_id']
            ]);
            
            Log::info('Réservation créée avec succès', [
                'id' => $reservation->id ?? 'non défini',
                'numero' => $reservationNumber,
                'bungalow_id' => $validated['bungalow_id']
            ]);
            
            return response()->json([
                'message' => 'Réservation créée avec succès', 
                'reservation_number' => $reservationNumber,
                'reservation' => $reservation
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la réservation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['message' => 'Erreur lors de la création de la réservation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retourne la disponibilité des bungalows pour une période donnée
     * Paramètres : start_date, end_date
     */
    public function getBungalowAvailability(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $bungalows = \App\Models\Bungalow::all();
        $reservations = \App\Models\Reservation::where(function($q) use ($validated) {
            $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
              ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
              ->orWhere(function($q2) use ($validated) {
                  $q2->where('start_date', '<=', $validated['start_date'])
                     ->where('end_date', '>=', $validated['end_date']);
              });
        })->get();
        $result = [
            'mer' => 0,
            'jardin' => 0,
        ];
        foreach ($bungalows as $bungalow) {
            $reserved = $reservations->where('bungalow_id', $bungalow->id)->count() > 0;
            if (!$reserved) {
                $result[$bungalow->type]++;
            }
        }
        return response()->json($result);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['client', 'createur'])->latest()->get();
        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Définition des règles de validation communes
        $requiredDate = 'required|date';
        $requiredIntMin1 = 'required|integer|min:1';
        $intMin0 = 'integer|min:0';
        
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'date_debut' => $requiredDate,
            'date_fin' => $requiredDate . '|after_or_equal:date_debut',
            'cree_par' => 'required|exists:utilisateurs,id',
            'bungalows' => 'array',
            'bungalows.*.id' => 'exists:bungalows,id',
            'bungalows.*.nb_personnes' => $requiredIntMin1,
            'tables_repas' => 'array',
            'tables_repas.*.id' => 'exists:table_repas,id',
            'tables_repas.*.nb_personnes' => $requiredIntMin1,
            'kayaks' => 'array',
            'kayaks.*.id' => 'exists:kayaks,id',
            'kayaks.*.nb_personnes' => $requiredIntMin1,
            'activites' => 'array',
            'activites.*.id' => 'exists:activites,id',
            'activites.*.date_activite' => $requiredDate,
            'activites.*.nb_personnes' => $intMin0,
            'activites.*.nb_enfants' => $intMin0,
            'activites.*.commentaire' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Création de la réservation
            $reservation = Reservation::create([
                'client_id' => $request->client_id,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'cree_par' => $request->cree_par,
            ]);

            // Ajout des bungalows
            if ($request->has('bungalows')) {
                foreach ($request->bungalows as $bungalow) {
                    $reservation->bungalows()->attach($bungalow['id'], [
                        'nb_personnes' => $bungalow['nb_personnes']
                    ]);
                }
            }

            // Ajout des tables de repas
            if ($request->has('tables_repas')) {
                foreach ($request->tables_repas as $table) {
                    $reservation->tablesRepas()->attach($table['id'], [
                        'nb_personnes' => $table['nb_personnes']
                    ]);
                }
            }

            // Ajout des kayaks
            if ($request->has('kayaks')) {
                foreach ($request->kayaks as $kayak) {
                    $reservation->kayaks()->attach($kayak['id'], [
                        'nb_personnes' => $kayak['nb_personnes']
                    ]);
                }
            }

            // Ajout des activités
            if ($request->has('activites')) {
                foreach ($request->activites as $activite) {
                    $reservation->activites()->attach($activite['id'], [
                        'date_activite' => $activite['date_activite'],
                        'nb_personnes' => $activite['nb_personnes'] ?? 0,
                        'nb_enfants' => $activite['nb_enfants'] ?? 0,
                        'commentaire' => $activite['commentaire'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Réservation créée avec succès',
                'reservation' => $reservation->load(['client', 'bungalows', 'tablesRepas', 'kayaks', 'activites'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la création de la réservation', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = Reservation::with(['client', 'createur', 'bungalows', 'tablesRepas', 'kayaks', 'activites'])->findOrFail($id);
        return response()->json($reservation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'exists:clients,id',
            'date_debut' => 'date',
            'date_fin' => 'date|after_or_equal:date_debut',
            'cree_par' => 'exists:utilisateurs,id',
            'bungalows' => 'array',
            'bungalows.*.id' => 'exists:bungalows,id',
            'bungalows.*.nb_personnes' => 'required|integer|min:1',
            'tables_repas' => 'array',
            'tables_repas.*.id' => 'exists:table_repas,id',
            'tables_repas.*.nb_personnes' => 'required|integer|min:1',
            'kayaks' => 'array',
            'kayaks.*.id' => 'exists:kayaks,id',
            'kayaks.*.nb_personnes' => 'required|integer|min:1',
            'activites' => 'array',
            'activites.*.id' => 'exists:activites,id',
            'activites.*.date_activite' => 'required|date',
            'activites.*.nb_personnes' => 'integer|min:0',
            'activites.*.nb_enfants' => 'integer|min:0',
            'activites.*.commentaire' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);

            // Mise à jour des champs de base
            if ($request->has('client_id')) {
                $reservation->client_id = $request->client_id;
            }
            if ($request->has('date_debut')) {
                $reservation->date_debut = $request->date_debut;
            }
            if ($request->has('date_fin')) {
                $reservation->date_fin = $request->date_fin;
            }
            if ($request->has('cree_par')) {
                $reservation->cree_par = $request->cree_par;
            }
            $reservation->save();

            // Mise à jour des bungalows
            if ($request->has('bungalows')) {
                $reservation->bungalows()->detach();
                foreach ($request->bungalows as $bungalow) {
                    $reservation->bungalows()->attach($bungalow['id'], [
                        'nb_personnes' => $bungalow['nb_personnes']
                    ]);
                }
            }

            // Mise à jour des tables de repas
            if ($request->has('tables_repas')) {
                $reservation->tablesRepas()->detach();
                foreach ($request->tables_repas as $table) {
                    $reservation->tablesRepas()->attach($table['id'], [
                        'nb_personnes' => $table['nb_personnes']
                    ]);
                }
            }

            // Mise à jour des kayaks
            if ($request->has('kayaks')) {
                $reservation->kayaks()->detach();
                foreach ($request->kayaks as $kayak) {
                    $reservation->kayaks()->attach($kayak['id'], [
                        'nb_personnes' => $kayak['nb_personnes']
                    ]);
                }
            }

            // Mise à jour des activités
            if ($request->has('activites')) {
                $reservation->activites()->detach();
                foreach ($request->activites as $activite) {
                    $reservation->activites()->attach($activite['id'], [
                        'date_activite' => $activite['date_activite'],
                        'nb_personnes' => $activite['nb_personnes'] ?? 0,
                        'nb_enfants' => $activite['nb_enfants'] ?? 0,
                        'commentaire' => $activite['commentaire'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Réservation mise à jour avec succès',
                'reservation' => $reservation->load(['client', 'bungalows', 'tablesRepas', 'kayaks', 'activites'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la mise à jour de la réservation', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);
            
            // Suppression des relations
            $reservation->bungalows()->detach();
            $reservation->tablesRepas()->detach();
            $reservation->kayaks()->detach();
            $reservation->activites()->detach();
            
            // Suppression de la réservation
            $reservation->delete();

            DB::commit();

            return response()->json(['message' => 'Réservation supprimée avec succès']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la suppression de la réservation', 'error' => $e->getMessage()], 500);
        }
    }
}
