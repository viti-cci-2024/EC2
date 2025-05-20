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
    
    // Afficher la structure de la table bungalows
    $stmt = $pdo->query("DESCRIBE bungalows");
    
    echo "<h1>Structure de la table bungalows</h1>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Afficher les données de la table bungalows
    $stmt = $pdo->query("SELECT * FROM bungalows LIMIT 10");
    
    echo "<h1>Données de la table bungalows</h1>";
    echo "<table border='1' cellpadding='5'>";
    
    // En-têtes de colonnes
    $first = true;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($first) {
            echo "<tr>";
            foreach (array_keys($row) as $key) {
                echo "<th>" . htmlspecialchars($key) . "</th>";
            }
            echo "</tr>";
            $first = false;
        }
        
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
