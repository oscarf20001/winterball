<?php
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
// Lade den Composer-Autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Erstelle ein Dotenv-Objekt und lade die .env-Datei
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// SMTP-Config
$mailHost = $_ENV['MAIL_HOST'];
$mailUsername = $_ENV['MAIL_USERNAME'];
$mailPassword = $_ENV['MAIL_PASSWORD'];   
$mailPort = $_ENV['MAIL_PORT'];                   
$mailEncryption = PHPMailer::ENCRYPTION_STARTTLS;


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

?>