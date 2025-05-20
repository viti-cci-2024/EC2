<?php
// Charger l'application Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Reservation;

// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier la connexion à la base de données
try {
    $connection = DB::connection()->getPdo();
    echo "<p>✅ Connexion à la base de données établie: " . DB::connection()->getDatabaseName() . "</p>";
} catch (\Exception $e) {
    echo "<p>❌ Erreur de connexion à la base de données: " . $e->getMessage() . "</p>";
    exit(1);
}

// Créer une réservation test directement
try {
    // Générer un numéro de réservation unique
    $reservationNumber = 'TEST-DIRECT-' . time();
    
    // Créer la réservation
    $reservation = new Reservation;
    $reservation->last_name = 'Test_Direct_' . time();
    $reservation->start_date = '2025-06-01';
    $reservation->end_date = '2025-06-05';
    $reservation->person_count = 2;
    $reservation->numero = $reservationNumber;
    $reservation->save();
    
    echo "<p>✅ Réservation créée avec succès: ID " . $reservation->id . ", Numéro " . $reservationNumber . "</p>";
    
    // Ajouter l'entrée dans la table pivot
    try {
        DB::table('reservation_bungalow')->insert([
            'reservation_id' => $reservation->id,
            'bungalow_id' => 1, // Utiliser le bungalow ID 1 (type mer)
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "<p>✅ Relation reservation-bungalow créée avec succès</p>";
    } catch (\Exception $e) {
        echo "<p>❌ Erreur lors de la création de la relation: " . $e->getMessage() . "</p>";
    }
    
    // Afficher toutes les réservations
    echo "<h2>Toutes les réservations</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Début</th><th>Fin</th><th>Personnes</th><th>Numéro</th><th>Créé le</th></tr>";
    
    $reservations = Reservation::orderBy('created_at', 'desc')->take(10)->get();
    
    foreach ($reservations as $res) {
        echo "<tr>";
        echo "<td>" . $res->id . "</td>";
        echo "<td>" . $res->last_name . "</td>";
        echo "<td>" . $res->start_date . "</td>";
        echo "<td>" . $res->end_date . "</td>";
        echo "<td>" . $res->person_count . "</td>";
        echo "<td>" . $res->numero . "</td>";
        echo "<td>" . $res->created_at . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Afficher les relations réservation-bungalow
    echo "<h2>Relations réservation-bungalow</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Reservation ID</th><th>Bungalow ID</th><th>Créé le</th></tr>";
    
    $relations = DB::table('reservation_bungalow')->orderBy('created_at', 'desc')->take(10)->get();
    
    foreach ($relations as $rel) {
        echo "<tr>";
        echo "<td>" . $rel->reservation_id . "</td>";
        echo "<td>" . $rel->bungalow_id . "</td>";
        echo "<td>" . $rel->created_at . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (\Exception $e) {
    echo "<p>❌ Erreur lors de la création de la réservation: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
