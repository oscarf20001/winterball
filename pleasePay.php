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
}

//WRITE ALL MAILS INTO AN ARRAY AND SAFE THIS ARRAY IN AN EXTERNAL FILE

//ALLE KÄUFER MAILS GÖNNEEEENN !!!!!!!!!!!! LIMIT STATEMENT ENTFERNEN, WENN IN PRODUCTION !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
#$sqlGetAllMails = "SELECT email, vorname, nachname, open FROM käufer WHERE email LIKE '%@gmail.com' AND ID >= 86 LIMIT 0";
$sqlGetAllMails = "SELECT email, vorname, nachname, open FROM testdata WHERE open > 0";
$stmt = $conn->prepare($sqlGetAllMails);
$stmt->execute();
$result = $stmt->get_result();
$allMails = array();
$iban = "DE 1210 0900 0087 1841 2006";

// Öffne oder erstelle die Logdatei
$logFile = __DIR__ . '/email_log_käufer.txt';
$logHandle = fopen($logFile, 'a');

if (!$logHandle) {
    die("Fehler beim Öffnen der Logdatei.");
}

while ($row = $result->fetch_assoc()) {
    $allMails[] = [
        'email' => $row['email'],
        'vorname' => $row['vorname'],
        'nachname' => $row['nachname'],
        'sum' => $row['open']
    ];
}

for ($i=0; $i < count($allMails); $i++) {
    //ID DIESER EINEN EMAIL AUFRUFEN, MIT DER DANN ALLE TICKETS GEFUNDEN WERDEN KÖNNEN, DIE AUF DIESE MAIL GEBUCHT WURDEN
    $sqlGetId = "SELECT ID FROM käufer WHERE email = '{$allMails[$i]['email']}'";
    $stmt = $conn->prepare($sqlGetId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row['ID'];

    //DATEN KÄUFER SPEICHERN = VORNAME, EMAIL (ZUM SENDEN), KOSTEN
    $nameKäufer = $allMails[$i]['vorname'];
    $emailKäufer = $allMails[$i]['email'];
    $sum = $allMails[$i]['sum'];

    //EMAIL VERSAND VORBEREITEN
    if ($emailKäufer && $sum && $nameKäufer) {
        // Nachricht und E-Mail-Inhalt erstellen
        $nachricht = "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Weihnachtsball</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
                th { background-color: #f2f2f2; }
                p { margin: 16px 0; }
            </style>
        </head>
        <body>
            <p>Hey " . htmlspecialchars($nameKäufer, ENT_QUOTES, 'UTF-8') . ",</p>
            <p><em>OMG‼️‼️‼️</em></p>
                        <p>
                            Am Freitag ist es so weit und der Winterball wird endlich stattfinden❤️‍🩹🥳
                        </p>
                        <p>
                            Für alle, die bisher noch nicht bezahlt haben haben wir insgesamt noch fünf Möglichkeiten dafür:
                        </p>
                        <p>
                            Montag, 3. Pause<br>
                            Dienstag, 2. & 3. Pause<br>
                            Mittwoch, 2. & 3. Pause<br>
                            Am Donnerstag findet voraussichtlich KEIN Verkauf mehr statt!!!<br>
                        </p>
                        <p>Hier nochmal eine kleine Übersicht deiner Reservierung:</p>
                        <table>
                            <thead style='border-left:2px solid black;'>
                                <tr>
                                    <th>Deine, noch zu begleichende, Summe:</th>
                                    <th>" . number_format($sum, 2, ',', '.') . "€</th>
                                </tr>
                            </thead>
                        </table>
                        <p>
                            Wir bieten auch die Möglichkeit einer Überweisung an. Überweise dazu die oben genannte Summe an dieses Konto:
                        </p>
                        <p>
                            <strong>IBAN:</strong> ".$iban."<br>
                            <strong>Name:</strong> Felix Wernecke<br>
                            <strong>Verwendungszweck:</strong> \"". $emailKäufer ." Winterball\"
                        </p>
                        <p>Wir freuen uns sehr auf euren Beitrag für den fancytastischen Winterball🤑💕</p>
                        <p>Allerliebste Grüße sendet euch,<br>Gordon</p>
        </body>
    </html>";

        try {
            $mail = new PHPMailer(true);
            
            // SMTP-Konfiguration
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Absender und Empfänger
            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Marie-Curie Gymnasium');
            $mail->addReplyTo('streiosc@curiegym.de', 'Oscar');
            $mail->addAddress($emailKäufer, $nameKäufer);

            // E-Mail-Inhalt
            $mail->isHTML(true);
            $mail->Subject = 'Buchungsbestätigung Winterball';
            $mail->Body = $nachricht;
            $mail->AltBody = 'Dies ist der Klartext-Inhalt der E-Mail.';

            // E-Mail senden
            if ($mail->send()) {
                echo "E-Mail erfolgreich an $emailKäufer gesendet.\n";
            } else {
                echo "Fehler beim Senden der E-Mail an $emailKäufer: " . $mail->ErrorInfo . "\n";
            }

            // Empfänger und Anhänge leeren
            $mail->clearAddresses();
            $mail->clearAttachments();
            sleep(0.5);
        } catch (Exception $e) {
            echo "Fehler beim Senden der E-Mail an $emailKäufer: {$mail->ErrorInfo}\n";
        }
    }
}

fclose($logHandle);

// Verbindung schließen
$conn->close();
 
#3 Checkbox für Einlass, wenn auf Gelände
?>