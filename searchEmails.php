<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Erstelle ein Dotenv-Objekt und lade die .env-Datei
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Greife auf die Umgebungsvariablen zu
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

// Erstellen einer MySQL-Verbindung mit den Umgebungsvariablen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);

// Verbindung auf UTF-8 setzen
$conn->set_charset("utf8");

// Überprüfen der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}else{
    //echo "<script>console.log('Verbindung zur Datenbank erfolgreich hergestellt!')</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = trim($_POST['query']);
    
    // Eingabe schützen (SQL-Injection verhindern)
    $safeQuery = $conn->real_escape_string($query);

    // Emails suchen
    $sqlSearchMail = "SELECT email FROM käufer WHERE email LIKE '$safeQuery%' LIMIT 10";
    $stmt = $conn->prepare($sqlSearchMail);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ergebnisse ausgeben
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p onclick='selectMail(\"{$row['email']}\")'>{$row['email']}</p>";
        }
    } else {
        echo "<p>Keine Ergebnisse gefunden</p>";
    }

    $conn->close();
}
?>