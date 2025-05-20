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
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
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
