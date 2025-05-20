<?php
// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Charger les variables d'environnement depuis .env
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Informations de connexion à la base de données depuis .env
$host = $_ENV['DB_HOST'] ?? 'localhost';
$database = $_ENV['DB_DATABASE'] ?? 'laravel';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

echo "<h1>Test de connexion à la base de données</h1>";
echo "<p>Host: {$host}, Database: {$database}, Username: {$username}, Port: {$port}</p>";

try {
    // Connexion PDO directe
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>✅ Connexion PDO réussie!</p>";
    
    // Vérifier si les tables existent
    $tables = ['reservations', 'bungalows', 'reservation_bungalow'];
    echo "<h2>Tables dans la base de données</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $exists = $stmt->rowCount() > 0;
        echo "<li>" . $table . ": " . ($exists ? "✅ Existe" : "❌ N'existe pas") . "</li>";
    }
    echo "</ul>";
    
    // Test d'insertion directe
    try {
        echo "<h2>Test d'insertion directe</h2>";
        
        // Insérer une réservation de test
        $stmt = $pdo->prepare("INSERT INTO reservations (last_name, start_date, end_date, person_count, numero, created_at, updated_at) 
                               VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        
        $lastName = "DB_Test_" . time();
        $startDate = "2025-07-01";
        $endDate = "2025-07-05";
        $personCount = 2;
        $numero = "DB-TEST-" . rand(1000, 9999);
        
        $stmt->execute([$lastName, $startDate, $endDate, $personCount, $numero]);
        
        $reservationId = $pdo->lastInsertId();
        echo "<p>✅ Réservation insérée avec ID: {$reservationId}</p>";
        
        // Insérer dans la table pivot
        $stmt = $pdo->prepare("INSERT INTO reservation_bungalow (reservation_id, bungalow_id, created_at, updated_at) 
                               VALUES (?, ?, NOW(), NOW())");
        $bungalowId = 1; // premier bungalow de type mer
        
        $stmt->execute([$reservationId, $bungalowId]);
        echo "<p>✅ Relation reservation_bungalow insérée avec succès</p>";
        
        // Afficher les dernières réservations
        echo "<h2>Dernières réservations</h2>";
        $stmt = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC LIMIT 5");
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Début</th><th>Fin</th><th>Personnes</th><th>Numéro</th><th>Créé le</th></tr>";
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['start_date'] . "</td>";
            echo "<td>" . $row['end_date'] . "</td>";
            echo "<td>" . $row['person_count'] . "</td>";
            echo "<td>" . $row['numero'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de l'insertion: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Erreur de connexion: " . $e->getMessage() . "</p>";
}
?>
