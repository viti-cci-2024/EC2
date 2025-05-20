<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Reservation;

// Vérifier la connexion à la base de données
try {
    $connection = DB::connection()->getPdo();
    echo "✅ Connexion à la base de données établie: " . DB::connection()->getDatabaseName() . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
    exit(1);
}

// Vérifier si les tables existent
echo "\nVérification des tables:\n";
$tables = ['reservations', 'bungalows', 'reservation_bungalow'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "✅ Table '$table' existe.\n";
        
        // Afficher la structure de la table
        echo "  Colonnes:\n";
        $columns = Schema::getColumnListing($table);
        foreach ($columns as $column) {
            echo "    - $column\n";
        }
        
        // Compter les enregistrements
        $count = DB::table($table)->count();
        echo "  Nombre d'enregistrements: $count\n";
    } else {
        echo "❌ Table '$table' n'existe PAS.\n";
    }
    echo "\n";
}

// Tester l'insertion d'une réservation
echo "Test d'insertion d'une réservation:\n";
try {
    // Vérifier si un bungalow existe
    $bungalow = DB::table('bungalows')->first();
    
    if (!$bungalow) {
        echo "❌ Aucun bungalow trouvé dans la table 'bungalows'. Création impossible.\n";
    } else {
        $reservationData = [
            'bungalow_id' => $bungalow->id,
            'last_name' => 'Test_' . time(),
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+3 days')),
            'person_count' => 2,
            'numero' => 'TEST-' . rand(1000, 9999),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Tenter d'insérer via Query Builder
        try {
            $id = DB::table('reservations')->insertGetId($reservationData);
            echo "✅ Réservation créée avec ID: $id (via Query Builder)\n";
        } catch (\Exception $e) {
            echo "❌ Erreur lors de l'insertion via Query Builder: " . $e->getMessage() . "\n";
            
            // Essayer une insertion SQL brute
            try {
                $result = DB::insert(
                    'INSERT INTO reservations (bungalow_id, last_name, start_date, end_date, person_count, numero, created_at, updated_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $reservationData['bungalow_id'],
                        $reservationData['last_name'],
                        $reservationData['start_date'],
                        $reservationData['end_date'],
                        $reservationData['person_count'],
                        $reservationData['numero'],
                        $reservationData['created_at'],
                        $reservationData['updated_at'],
                    ]
                );
                echo "✅ Réservation créée avec succès (via SQL brute)\n";
            } catch (\Exception $e) {
                echo "❌ Erreur lors de l'insertion SQL brute: " . $e->getMessage() . "\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "❌ Erreur générale lors du test d'insertion: " . $e->getMessage() . "\n";
}

echo "\nTest terminé.\n";
