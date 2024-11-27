<?php
// Debugging: Rohdaten aus POST-Body loggen
error_log("Rohdaten aus php://input: " . file_get_contents('php://input'));

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dotenv laden
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Lade die Umgebungsvariablen
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

$mailHost = $_ENV['MAIL_HOST'];
$mailUsername = $_ENV['MAIL_USERNAME'];
$mailPassword = $_ENV['MAIL_PASSWORD'];
$mailPort = $_ENV['MAIL_PORT'];

// Verbindung mit der Datenbank herstellen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    logError("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
    sendJsonResponse(['error' => 'Datenbankverbindung fehlgeschlagen']);
    exit;
}

// Eingabedaten abrufen und validieren
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// JSON-Fehler prüfen
if (json_last_error() !== JSON_ERROR_NONE) {
    sendJsonResponse(['error' => 'Fehler beim Lesen von JSON-Daten: ' . json_last_error_msg()]);
    exit;
}

// E-Mail-Adresse validieren
$email = $data['email'] ?? null;
if (empty($email)) {
    sendJsonResponse([
        'error' => 'Ungültige oder fehlende E-Mail-Adresse',
        'debug' => [
            'input' => $data,
            'raw_post' => $input,
        ]
    ]);
    exit;
}

$addedValue = 2.50;

// Käufer-ID und Name abrufen
$sqlGetKaeuferId = "SELECT ID, vorname FROM käufer WHERE email = ?";
$stmt = $conn->prepare($sqlGetKaeuferId);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $k_id = $row['ID'];
    $vorname = $row['vorname'];
} else {
    sendJsonResponse(['error' => 'Käufer nicht gefunden']);
    exit;
}
$stmt->close();

// Offene Summe abrufen
$sqlGetSum = "SELECT sum FROM käufer WHERE ID = ?";
$stmt = $conn->prepare($sqlGetSum);
$stmt->bind_param('i', $k_id);
$stmt->execute();
$result = $stmt->get_result();
$sum = $result->fetch_assoc()['sum'] ?? null;
$stmt->close();

if ($sum === null) {
    sendJsonResponse(['error' => 'Fehler beim Abrufen der offenen Summe']);
    exit;
}

// E-Mail erstellen und senden
$mail = new PHPMailer(true);

try {
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
            <p>Deine Kosten in Höhe von<br><br>
            ". htmlspecialchars($sum) . "€<br><br>
            wurden voll und ganz beglichen. Wie episch!</p>
            <p>Einige letzte Infos für dich: <br>- Der Einlass ist 19 - 20 Uhr. Bei einer Ankunft nach 20 Uhr fällt eine zusätzliche Gebühr von ".number_format($addedValue, 2, ',', '.')."€ an. <br>- Der Eröffnungstanz der 12. Klassen beginnt um 20:15 Uhr.</p>
            
            <p>Wir danken, freuen uns zusammen mit dir auf den 13.12 und wünschen dir eine frohe Vorweihnachtszeit!
            Mit freundlichen Grüßen,<br>Gordon!</p>
        </body>
        </html>
    ";

    // SMTP-Konfiguration
    $mail->isSMTP();
    $mail->Host       = $mailHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailUsername;
    $mail->Password   = $mailPassword;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $mailPort;
    $mail->CharSet    = 'UTF-8';

    // Empfänger
    $mail->setFrom($mailUsername, 'Marie-Curie Gymnasium');
    $mail->addReplyTo('streiosc@curiegym.de', 'Oscar');
    $mail->addAddress($email, $vorname);

    // Nachricht
    $mail->isHTML(true);
    $mail->Subject = 'Ticketbestätigung Winterball';
    $mail->Body    = $nachricht;

    $mail->send();
    sendJsonResponse(['message' => 'E-Mail erfolgreich gesendet', 'sum' => number_format($sum, 2)]);
} catch (Exception $e) {
    logError("PHPMailer Fehler: " . $mail->ErrorInfo);
    sendJsonResponse(['error' => 'E-Mail konnte nicht gesendet werden']);
}

// Hilfsfunktionen
function sendJsonResponse(array $response){
    header('Content-Type: application/json');
    echo json_encode($response);
}

function logError($message){
    error_log($message . PHP_EOL, 3, __DIR__ . '/error_log.txt');
}
?>
