<?php
// Charger les variables d'environnement Laravel
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connexion à la base de données
$host = $_ENV['DB_HOST'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer les 10 dernières réservations
    $stmt = $pdo->query("SELECT r.id, r.last_name, r.start_date, r.end_date, r.person_count, r.numero, r.created_at, 
                          rb.bungalow_id
                          FROM reservations r 
                          LEFT JOIN reservation_bungalow rb ON r.id = rb.reservation_id
                          ORDER BY r.created_at DESC LIMIT 10");
    
    echo "<h1>Dernières réservations</h1>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Début</th><th>Fin</th><th>Personnes</th><th>Numéro</th><th>Bungalow ID</th><th>Créé le</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['person_count']) . "</td>";
        echo "<td>" . htmlspecialchars($row['numero']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bungalow_id'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
