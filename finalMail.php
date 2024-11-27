<?php
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
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    sendJsonResponse(['error' => 'Ungültige E-Mail-Adresse']);
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
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                p { margin: 16px 0; }
            </style>
        </head>
        <body>
            <p>Hey " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . ",</p>
            <p>Deine Kosten in Höhe von</p><br>
            <p> ". htmlspecialchars($sum) . "€</p><br>
            <p>wurde voll und ganz beglichen. Wie episch!</p>
            <p>Einige letze Infos für dich: Der Einlass ist 19 - 20 Uhr. Bei einer Ankunft nach 20 Uhr fällt eine zusätzliche Gebür von ".$addedValue." an. Der Eröffnungstanz der 12. Klassen beginnt um 20:15 Uhr.</p>
            <p>Beim Eintritt zeige bitte folgende Codes dem Einlass vor:</p>
            <table>
                <thead>
                    <tr>
                        <th>Vorname</th>
                        <th>Nachname</th>
                        <th>Summe</th>
                    </tr>
                </thead>
                <tbody>";


                //Tickets for this Käufer
                $KäuferAllTickets = "SELECT email,vorname,nachname,sum FROM tickets WHERE käufer_ID = $id";
                $stmt = $conn->prepare($KäuferAllTickets);
                $stmt->execute();
                $result = $stmt->get_result();

                // Füge Zeilen für jedes Ticket hinzu
                while ($row = $result->fetch_assoc()) {
                    $vorname = htmlspecialchars($row['vorname'], ENT_QUOTES, 'UTF-8');
                    $nachname = htmlspecialchars($row['nachname'], ENT_QUOTES, 'UTF-8');
                    $sum = number_format((float)$row['sum'], 2, ',', '.');
        
                    $nachricht .= "
                    <tr>
                        <td>$vorname</td>
                        <td>$nachname</td>
                        <td>" . $sum . "€</td>
                    </tr>";
                }
        
                $nachricht .= "
                </tbody>
            </table>

            <p>Wir danken, freuen uns zusammen mit dir auf den 13.12 und wünschen dir eine frohe Vorweihnachtszeit!</p>
            <p>Mit freundlichen Grüßen,<br>Gordon!</p>
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