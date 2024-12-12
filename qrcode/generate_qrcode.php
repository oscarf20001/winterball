<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Dotenv laden
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Lade die Umgebungsvariablen: Datenbank
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

// Lade die Umgebungsvariablen: Mailserver
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

// Funktion zur Generierung des QR-Codes
function generateQRCode($data, $mail, $labelText, $mailHost,$mailUsername,$mailPassword,$mailPort, $outputFile = null) {
    // Versuch, den QR-Code zu erstellen
    try {
        // Erstellen des QR-Code Builders mit den übergebenen Daten
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: $labelText,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        // QR-Code generieren
        $result = $builder->build();

        // Debug: Erfolgreiches Erstellen des QR-Codes
        logDebug('✅ QR-Code erfolgreich erstellt für: '.$labelText);
        
        // Wenn ein Dateipfad übergeben wurde, QR-Code speichern
        if ($outputFile) {
            $result->saveToFile($outputFile);
            logDebug('✅ QR-Code gespeichert unter: ' . $outputFile);
        }

        //QR-Code per Mail versenden
        sendQRCodeByMail($mail, $labelText, $outputFile,$mailHost,$mailUsername,$mailPassword,$mailPort);

        // Rückgabe der Data-URI, falls benötigt
        return $result->getDataUri();

    } catch (Exception $e) {
        // Fehlerbehandlung: falls ein Fehler beim Erstellen des QR-Codes auftritt
        logDebug('❌ Fehler beim Erstellen des QR-Codes: '.$e->getMessage());
        // Optional: Fehlermeldung an den Benutzer ausgeben oder weitergeben
        return '❌ Fehler beim Erstellen des QR-Codes: '.$e->getMessage();
    }
}

//Funktion zum Senden der QR-Codes per Mail
function sendQRCodeByMail($empfeangerMail,$cptName,$attachmentFile,$mailHost,$mailUsername,$mailPassword,$mailPort){
    // E-Mail erstellen
    $mail = new PHPMailer(true);
    $parts = explode(" ",$cptName);
    $firstName = $parts[0];

    //Try-Block für Mailversand und weitere Definitionen
    try {
        //DEFINITION NACHRICHT
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
                <p>Hey ".$firstName.",</p>
                    <p>ENDLICH ist es soweit! 🎄 🎅 🤶<br>Ihr habt alle so tapfer und geduldig auf eure Tickets gewartet, jetzt kriegt ihr sie.<br>
                    Wir möchten euch, wo wir gerade schon alle hier sind, noch einige letzte wichtige Infos mitgeben und FAQs beantworten:<br><br>

                    <strong>\"gibts irgendwie Security Menschen, die den Einlass kontrollieren oder macht ihr das einfach?\"<br></strong>
                    <strong>Ja</strong>, es wird beim Betreten des Geländes eine Personalausweis-, als auch Taschenkontrolle geben.<br><br>

                    <strong>\"kann man bei der gaderobe morgen auch nen Rucksack abgeben? Bestimmt oder?\"<br></strong>
                    <strong>Ja</strong>, unsere fleißigen Elfchen werden euch auch mit euren Rucksäcken helfen können. Haltet euer Gepäck jedoch bitte möglichst klein und seht davon ab, irgendwelche Wertgegenstände, außer den amtlichen Lichtbildausweis, einzupacken.<br><br>

                    <strong>\"Habt ihr nh Dresscode?\"<br></strong>
                    Naja, joa, wir würden uns freuen, wenn ihr nicht in Jogginghose antanzt, allerdings braucht ihr euch auch nicht wie zu einer Hochzeit rausputzen.<br><br>

                    <strong>🚬 Ob man auf dem Gelände rauchen kann?<br></strong>
                    Das ist möglich, solange ihr das Veranstaltungsgelände nicht verlasst. Das bringt uns auch zur nächsten Frage:<br><br>

                    <strong>🚶‍♂️ Dürfen wir rausgehen?<br></strong>
                    Natürlich dürft ihr das, seid allerdings gewarnt, dass wer das Veranstaltungsgelände verlässt, der verlässt auch endgültig die Veranstaltung – der Weihnachtsmann hat da dann auch kein Nachsehen mehr. Ansonsten dürft ihr euch auf dem Gelände frei bewegen.<br><br>

                    <strong>Ihr werdet außerdem von der Veranstaltung ausgeschlossen, wenn: <br></strong>
                    - ❌ ihr euch daneben benehmt<br>
                    - ❌ ihr beim Schmuggeln erwischt werdet<br>
                    - ❌ ihr euer Armband verliert<br>
                    Genannte Punkte führen unwiderruflich zum sofortigen Ausschluss von der Veranstaltung.<br><br>

                    Wir bitten um das Benehmen eurerseits, damit der aktuelle 11. Jahrgang des MCGs auch nach uns diese Veranstaltung durchführen kann.<br><br>

                    Ihr werdet nicht auf das Gelände gelassen, wenn ihr bereits vor Eintritt zu betrunken seid.<br><br>

                    Der Einlass findet von 19:15 bis 21:00 Uhr statt. Wer um 20:00 Uhr nicht durch die Kontrolle durch ist und die Veranstaltung trotzdem betreten möchte, muss an der Abendkasse 2,50 € auf seinen ursprünglichen Ticketpreis bezahlen, da nach 20 Uhr die Eröffnung stattfindet.<br><br>

                    Wir bitten den 12. Jahrgang des MCG, pünktlich vor 20:00 Uhr in der Location einzutreffen. Der Eröffnungstanz dieser beginnt um 20:15 Uhr 💃
                    </p>

                    Die Veranstaltung wird ca. um 00:00 Uhr bis 00:30 Uhr enden. Wir würden uns freuen, wenn sich am Ende der Veranstaltung noch einige freiwillige Helfer finden, die mit Gordon und dem gesamten Orga-Team den Saal schnell aufräumen.<br><br>

                    Sollte es irgendwelche Probleme oder Anregungen sowohl technischer als auch allgemeiner Natur geben, antwortet gern auf diese Mail, wendet euch an 'streiosc@curiegym.de' oder sprecht uns persönlich an!<br>
                    Im Anhang findet ihr euer Ticket (QR-Code)<br><br>

                    <p>🌟 🎁 Wir danken und freuen uns riesig zusammen mit dir auf den 13.12. und wünschen dir eine frohe Vorweihnachtszeit!❄️ ⛄<br><br>
                    Mit freundlichen Grüßen,<br><strong>Gordon!</strong></p>
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
        $mail->addAddress($empfeangerMail, $firstName);
        $mail->addReplyTo('streiosc@curiegym.de', 'Oscar');

        // Anhänge
        $mail->addAttachment($attachmentFile,'qrcode.png');

        // Nachricht
        $mail->isHTML(true);
        $mail->Subject = 'Ticketbestätigung Winterball';
        $mail->Body    = $nachricht;

        $mail->send();
        logDebug('✅ E-Mail für '.$cptName.' erfolgreich mit QR-Code gesendet an: ' . $empfeangerMail);
    } catch (Exception $e) {
        logDebug('❌ Fehler beim Versenden der E-Mail: ' . $mail->ErrorInfo);
    }
}

// Hilfsfunktion für Debugging-Logs
function logDebug($message) {
    // Log-Datei für Debugging (kann auch angepasst werden)
    $debugLogFile = 'debug.log';
    $debugLog = fopen($debugLogFile, 'a');
    fwrite($debugLog, date('Y-m-d H:i:s').' - '.$message."\r\n");
    fclose($debugLog);
}

$sqlIdArray = "SELECT ID FROM tickets ORDER BY ID";
$stmt = $conn->prepare($sqlIdArray);
$stmt->execute();
$result = $stmt->get_result();

$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = $row['ID'];
}

//ENTFERNEN, WENN IN PRODUKTION: 


if(count($ids) > 0){
    $sql = "SELECT vorname, nachname, email FROM tickets WHERE ID = ? LIMIT 1";
    
    // Vorbereitung der SQL-Abfrage
    $stmt = $conn->prepare($sql);

    // Überprüfe, ob das Statement korrekt vorbereitet wurde
    if ($stmt === false) {
        die('Fehler bei der Vorbereitung der SQL-Abfrage: ' . $conn->error);
    }

    // Schleife durch jede ID im Array
    foreach ($ids as $id) {

        // Überprüfe, ob der Zähler 25 erreicht hat
        if ($count >= 25) {
            break;  // Schleife stoppen, wenn 25 Durchläufe erreicht sind
        }

        // Binde die aktuelle ID als Parameter
        $stmt->bind_param('i', $id);

        // Führe die Abfrage aus
        $stmt->execute();

        // Hole das Ergebnis
        $result = $stmt->get_result();

        // Überprüfe, ob ein Ergebnis gefunden wurde
        if ($result->num_rows > 0) {
            // Hole die Daten
            $row = $result->fetch_assoc();
            
            // Ausgabe der Daten (z.B. Vorname, Nachname, und E-Mail)
            echo "Vorname: " . $row['vorname'] . "<br>";
            echo "Nachname: " . $row['nachname'] . "<br>";
            echo "E-Mail: " . $row['email'] . "<br><br>";

            $action = 'einlass';
            $lstname = $row['nachname'];
            $prename = $row['vorname'];
            $name = $prename.' '.$lstname;
            $mail = $row['email'];
            $data = 'https://www.curiegymnasium.de/qrcode/scanQrCode.php?action='.$action.'&name='.$lstname.'&prename='.$prename.'&mail='.$mail;
            generateQRCode($data, $mail, $name,$mailHost,$mailUsername,$mailPassword,$mailPort, __DIR__ . '/codes/qrcode_einlass_'.$name.'.png');
        } else {
            echo "Keine Daten gefunden für ID: " . $id . "<br><br>";
        }

        $count++;
    }

    // Schließe das Statement
    $stmt->close();
}

#echo '<pre>';
#print_r($ids);
#echo '</pre>';

#login einlass
#2,5 window
#signs -> Security, Einlass, Garderobe, Fotobox
#wunschsongs formular

?>