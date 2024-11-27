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

$email = $_POST['email'];
// Get buyer ID
$sqlGetKaeuferId = "SELECT ID, vorname FROM käufer WHERE email = ?";
$stmt = $conn->prepare($sqlGetKaeuferId);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

// Sicherstellen, dass ein Ergebnis vorhanden ist
if ($row = $result->fetch_assoc()) {
    $k_id = $row['ID'];
    $vorname = $row['vorname'];
} else {
    // Fehlerbehandlung, wenn keine Daten gefunden wurden
    $k_id = null;
    $vorname = null;
    exit();
}
$stmt->close();

if (!$k_id) {
    echo json_encode(['error' => 'Buyer not found']);
    exit;
}

$sqlGetSum = "SELECT sum FROM käufer WHERE ID = ?";
$stmt = $conn->prepare($sqlGetSum);
$stmt->bind_param('i', $k_id);
$stmt->execute();
$result = $stmt->get_result();
$sum = $result->fetch_assoc()['sum'] ?? null;
$stmt->close();

header('Content-Type: application/json');
echo json_encode([
    'sum' => number_format($sum, 2)
]);

$mail = new PHPMailer(true);

try {
    // Erstelle eine PHPMailer-Instanz

    //PREPARE NACHRICHT
    $nachricht = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Weihnachtsball</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                    border: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
                p {
                    margin: 16px 0;
                }
            </style>
        </head>
        <body>
            <p>Hey " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . ",</p>
            <p>Wir wünschen eine frohe Vorweihnachtszeit und freuen uns auf dich!</p>
            <p>Mit freundlichen Grüßen,<br>Gordon</p>
        </body>
        </html>
    ";

    // SMTP-Config
    $mailHost = $_ENV['MAIL_HOST'];
    $mailUsername = $_ENV['MAIL_USERNAME'];
    $mailPassword = $_ENV['MAIL_PASSWORD'];   
    $mailPort = $_ENV['MAIL_PORT'];                   
    $mailEncryption = PHPMailer::ENCRYPTION_STARTTLS;

    // Servereinstellungen
    $mail->isSMTP();                      // SMTP-Modus aktivieren
    $mail->Host       = $mailHost;        // SMTP-Server
    $mail->SMTPAuth   = true;             // SMTP-Authentifizierung aktivieren
    $mail->Username   = $mailUsername;    // SMTP-Benutzername (deine Gmail-Adresse)
    $mail->Password   = $mailPassword;    // SMTP-Passwort (App-Passwort)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Verschlüsselung
    $mail->Port       = $mailPort;        // Port (587 für TLS)
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Absender und Empfänger
    $mail->setFrom($mailUsername, 'Marie-Curie Gymnasium'); // Absenderadresse und Name
    $mail->addReplyTo('streiosc@curiegym.de', 'Oscar'); // Reply-To-Adresse
    $mail->addAddress($email, $vorname); // Empfängeradresse

    // Nachricht konfigurieren
    $mail->isHTML(true); // HTML-Format aktivieren
    $mail->Subject = 'Buchungsbestätigung Winterball'; // Betreff
    $mail->Body    = $nachricht; // HTML-Inhalt
    $mail->AltBody = 'Dies ist der Klartext-Inhalt der E-Mail.'; // Klartext-Inhalt (falls kein HTML unterstützt wird)

    // E-Mail senden
    if ($mail->send()) {
        logMessage("Email an Käufer ($email) via new SMTP versendet");
    } else {
        $errorInfo = $mail->ErrorInfo;
        logMessage("Fehler: E-Mail konnte nicht gesendet werden. Fehlerinfo: $errorInfo");
        echo 'E-Mail konnte nicht via new SMTP gesendet werden. Fehler: ' . $errorInfo;
    }
} catch (Exception $e) {
    logMessage("Fehler: Emailversand an Käufer ($email) fehlgeschlagen: {$mail->ErrorInfo}");
    echo "Fehler beim Senden der E-Mail: {$mail->ErrorInfo}";
}