<?php

// Script simple pour vérifier le contenu de la base de données

// Récupérer les paramètres depuis .env
$envFile = file_get_contents(__DIR__ . '/.env');
preg_match('/DB_HOST=(.*)/', $envFile, $hostMatches);
preg_match('/DB_USERNAME=(.*)/', $envFile, $userMatches);
preg_match('/DB_PASSWORD=(.*)/', $envFile, $passMatches);
preg_match('/DB_DATABASE=(.*)/', $envFile, $dbMatches);

$host = trim($hostMatches[1]);
$user = trim($userMatches[1]);
$pass = trim($passMatches[1]);
$database = trim($dbMatches[1]);

echo "Connexion à la base de données: $host / $database\n";

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion réussie\n\n";
    
    // Vérifier les tables
    $tables = ['reservations', 'bungalows', 'reservation_bungalow'];
    
    foreach ($tables as $table) {
        echo "Table: $table\n";
        
        // Récupérer les colonnes
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Colonnes: " . implode(", ", $columns) . "\n";
        
        // Récupérer le nombre d'enregistrements
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "Nombre d'enregistrements: $count\n";
        
        // Si la table a des enregistrements, les afficher
        if ($count > 0) {
            $stmt = $pdo->query("SELECT * FROM $table");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Contenu:\n";
            print_r($rows);
        }
        
        echo "\n";
    }
    
    // Rechercher spécifiquement une réservation avec le numéro donné
    $reservationNumber = 'CH25050017';
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE numero = ?");
    $stmt->execute([$reservationNumber]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Recherche du numéro de réservation: $reservationNumber\n";
    if ($reservation) {
        echo "✅ Réservation trouvée:\n";
        print_r($reservation);
    } else {
        echo "❌ Aucune réservation trouvée avec ce numéro\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
}
