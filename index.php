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

// Beispiel: Erstellen einer MySQL-Verbindung mit den Umgebungsvariablen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);

// Verbindung auf UTF-8 setzen
$conn->set_charset("utf8");

// Überprüfen der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>