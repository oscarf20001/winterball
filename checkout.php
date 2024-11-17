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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $paid = $_POST['paid'] ?? 0;
    echo "Der berechnte Betrag ist: " . number_format($paid,2). " Euro\n";

    $methodStr = $_POST['method'];

    //KÄUFER ID GÖNNEN
    $sqlGetKaeuferId = "SELECT ID FROM käufer WHERE email = '".$_POST["email"]."';";
    $stmt = $conn->prepare($sqlGetKaeuferId);
    $stmt->execute();
    $result = $stmt->get_result();
    $k_id = $result->fetch_assoc();
    $k_id = $k_id['ID'];
    $stmt->close();
    date_default_timezone_set('UTC');
    $timestamp = date("Y/m/d - H:i:s");
    echo "Käufer-ID: " . $k_id . "\n";
    echo "Methode: " . $methodStr . "\n";
    echo "Zeit: " . $timestamp;

    //GELD BUCHEN
    $sqlBuchen = "UPDATE `käufer` SET `paid` = paid + $paid, `method` = '$methodStr', `date_paid` = '$timestamp' WHERE `käufer`.`ID` = $k_id;";
    $stmt = $conn->prepare($sqlBuchen);
    $stmt->execute();
    $stmt->close();

    //DIFFERENZ CHECKEN
    $sqlDif = "SELECT `open` FROM `käufer` WHERE ID = $k_id";
    $stmt = $conn->prepare($sqlDif);
    $stmt->execute();
    $result = $stmt->get_result();
    $diff = $result->fetch_assoc();
    $diff = $diff['open'];
    $stmt->close();

    if($diff <= 0){
        //STATUS SETZEN
        $sqlStatus = "UPDATE `käufer` SET `status` = 1 WHERE ID = $k_id";
        $stmt = $conn->prepare($sqlStatus);
        $stmt->execute();
        $stmt->close();
    }else{
        $sqlStatus = "UPDATE `käufer` SET `status` = 0 WHERE ID = $k_id";
        $stmt = $conn->prepare($sqlStatus);
        $stmt->execute();
        $stmt->close();
    }

}else{
    echo "Ungültig!";
}
?>