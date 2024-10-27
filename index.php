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
    echo "<script>console.log('Verbindung zur Datenbank erfolgreich hergestellt!')</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung Winterball 2024 MCG</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- HEADLINE -->
    <header id="head">
        <h2>Anmeldung zum Winterball des MCG 2024</h2>
    </header>

    <!-- FORMULAR CONTENT -->
    <form action="index.php" method="POST" id="main" class="main">

        <!-- ÜBER DIE PERSON, DIE DIE TICKETS KAUFT -->
        <div class="aboutYou">

            <!-- INFO HEADLINE ABOUT U -->
            <h3>Zuerst etwas über <span style="color: #52c393">dich!</span></h3>

            <div class="aboutYouInputs">
                <!-- ABOUT YOU FORM -->
                <div class="input-field name">
                    <input type="text" id="name" name="nachname" >
                    <label for="nachname">Dein Nachname:<sup>*</sup></label>
                </div>
                <div class="input-field vorname">
                    <input type="text" id="vorname" name="vorname" >
                    <label for="vorname">Dein Vorname:<sup>*</sup></label>
                </div>
                <div class="input-field email">
                    <input type="email" id="email" name="email" >
                    <label for="email">Deine Email-Adresse:<sup>*</sup></label>
                </div>
                <div class="input-field telNumber">
                    <input type="tel" id="telNumber" name="telNumber" >
                    <label for="telNumber">Deine Telefonnummer:<sup>*</sup></label>
                </div>
                <div class="input-field age">
                    <input type="number" id="age" name="age" >
                    <label for="age">Dein Alter:<sup>*</sup></label>
                </div>
                <div class="input-field klasse">
                    <input type="text" id="klasse" name="klasse" >
                    <label for="klasse">Deine Klasse:<sup>*</sup> (Format: Klassenstufe/Zug)</label>
                </div>
                <div class="input-field cntTickets">
                    <input type="number" id="cntTickets" name="cntTickets" min="1" max="3" >
                    <label for="cntTickets">Anzahl an Tickets:<sup>*</sup></label>
                </div>
            </div>
            <p>Aktuelle Anzahl der Tickets: <span id="ticketCountDisplay">1</span></p>
        </div>

        <!-- INFO HEADLINE ABOUT U -->
        <h3>Für wen willst du <span style="color: #52c393">Tickets</span> kaufen?</h3>

        <!-- FORM TICKETS -->
        <div id="ticketsContainer">
            <div class="ticket">
                <h3>Ticket Nr. <span>1</span></h3>

                <div class="input-field ticketName">
                    <input type="text" name="ticketName" required>
                    <label for="ticketName">Name:<sup>*</sup></label>
                </div>
                <div class="input-field ticketVorName">
                    <input type="text" id="" name="ticketVorName" required>
                    <label for="ticketVorName">Vorname:<sup>*</sup></label>
                </div>
                <!--<div class="input-field ticketEmail">
                    <input type="email" id="" name="ticketEmail">
                    <label for="ticketEmail">Email:<sup>*</sup></label>
                </div>
                <div class="input-field ticketAge">
                    <input type="text" id="" name="ticketAge">
                    <label for="ticketAge">Alter:<sup>*</sup></label>
                </div>-->
            </div>
        </div>

        <input type="submit" value="Daten absenden!">

    </form>

    <script>
        // Sicherstellen, dass das DOM vollständig geladen ist
        document.addEventListener("DOMContentLoaded", function() {
            const cntTicketsInput = document.getElementById("cntTickets");
            const ticketsContainer = document.getElementById("ticketsContainer");
            const ticketCountDisplay = document.getElementById("ticketCountDisplay");

            function generateTickets(count) {

                // BEI NEUAUFRUF DER FUNKTION ALLE GENERIERTEN TICKETS LÖSCHEN UND NEUE ERSTELLEN
                ticketsContainer.innerHTML = '';

                // ANZHAL DER TICKETS AUSGEBEN UND RICHTIG STELLEN
                if(isNaN(count)){
                    ticketCountDisplay.innerText = 0;
                }else if(count > 2){
                    ticketCountDisplay.innerText = 2;
                }else{
                    ticketCountDisplay.innerText = parseInt(count);
                }

                // TICKETS GENERIEREN
                if(count > 1){
                    for (let i = 1; i < 3; i++) {
                        const ticketDiv = document.createElement("div");
                        ticketDiv.classList.add("ticket");
                        ticketDiv.innerHTML = `
                        <div class="ticket">
                            <h3>Ticket Nr. <span>${i}</span></h3>
                            <div class="input-field ticketName">
                                <input type="text" name="ticketName${i}" required>
                                <label for="ticketName${i}">Name:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketVorName">
                                <input type="text" name="ticketVorName${i}" required>
                                <label for="ticketVorName${i}">Vorname:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketEmail">
                                <input type="email" name="ticketEmail${i}">
                                <label for="ticketEmail${i}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" name="ticketAge${i}">
                                <label for="ticketAge${i}">Alter:<sup>*</sup></label>
                            </div>
                        </div>
                        `;
                        ticketsContainer.appendChild(ticketDiv);
                    }
                }else if(count = 1){
                    const ticketDiv = document.createElement("div");
                    ticketDiv.classList.add("ticket");
                    ticketDiv.innerHTML = `
                        <div class="ticket">
                            <h3>Ticket Nr. <span>${count}</span></h3>
                            <div class="input-field ticketName">
                                <input type="text" name="ticketName${count}" required>
                                <label for="ticketName${count}">Name:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketVorName">
                                <input type="text" name="ticketVorName${count}" required>
                                <label for="ticketVorName${count}">Vorname:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketEmail">
                                <input type="email" name="ticketEmail${count}">
                                <label for="ticketEmail${count}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" name="ticketAge${count}">
                                <label for="ticketAge${count}">Alter:<sup>*</sup></label>
                            </div>
                        </div>
                    `;
                    ticketsContainer.appendChild(ticketDiv);
                }else{
                    console.log("Ungültige Anzahl");
                }
            }

            cntTicketsInput.addEventListener("input", function() {
                const ticketCount = parseInt(cntTicketsInput.value);
                generateTickets(ticketCount);
            });

            generateTickets(parseInt(cntTicketsInput.value));
        });

        // OVERFLOW HERRAUSFINDEN
        const elements = document.querySelectorAll('*'); // Wählt alle Elemente aus

        elements.forEach(element => {
            const rect = element.getBoundingClientRect(); // Hol die Position und Dimension des Elements
            if (rect.width > window.innerWidth || rect.height > window.innerHeight) {
                console.log('Overflow Element:', element); // Gibt das Element in der Konsole aus
            }
        });

    </script>

    <?php

    $nachNameKäufer = "";
    $vorNameKäufer = "";
    $emailKäufer = "";
    $telNummerKäufer = "";
    $ageKäufer = "";
    $klasseKäufer = "";
    $countTicketsKäufer = "";

        //DATEN AUS DEM FORMULAR ABRUFEN
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //MAYBE MIT COOKIES ARBEITEN, UM DATEN DES KÄUFERS IM FORMULAR ZU SPEICHERN, DAMIT SIE NICHT VERLOREN GEHEN, WENN MAN ABSENDET UND ES EINEN ERROR GIBT? 

            global $nachNameKäufer,$vorNameKäufer,$emailKäufer,$telNummerKäufer,$ageKäufer,$klasseKäufer,$countTicketsKäufer;
            //DATEN KÄUFER
                $nachNameKäufer = htmlspecialchars($conn->real_escape_string($_POST["nachname"]));
                $vorNameKäufer = htmlspecialchars($conn->real_escape_string($_POST["vorname"]));
                $emailKäufer = htmlspecialchars($conn->real_escape_string($_POST["email"]));
                $telNummerKäufer = htmlspecialchars($conn->real_escape_string($_POST["telNumber"]));
                $ageKäufer = htmlspecialchars($conn->real_escape_string($_POST["age"]));
                $klasseKäufer = htmlspecialchars($conn->real_escape_string($_POST["klasse"]));
                $countTicketsKäufer = htmlspecialchars($conn->real_escape_string($_POST["cntTickets"]));

            //ANDERE VARIABLEN
                $money = 0.00;
                $money1 = 0.00;
                $money2 = 0.00;

            if($countTicketsKäufer == 2){
                
                //DIESER BLOCK WIRD AUSGEFÜHRT, WENN DER KÄUFER MEHR ALS EIN TICKET KAUFT
                //DATEN TICKET NR.1
                $ageTicket1 = htmlspecialchars($conn->real_escape_string($_POST["ticketAge1"]));
                $emailTicket1 = htmlspecialchars($conn->real_escape_string($_POST["ticketEmail1"]));

                $nachNameTicket1 = htmlspecialchars($conn->real_escape_string($_POST["ticketName1"]));
                $vorNameTicket1 = htmlspecialchars($conn->real_escape_string($_POST["ticketVorName1"]));

                //KOMPLETTEN NAMEN ERSTELLEN
                $vollständigNameTicket1 = $vorNameTicket1 . " " . $nachNameTicket1;
                $resultTicket1 = checkNameKombinationOfMCG($vollständigNameTicket1);
                if($resultTicket1['found']){
                    $money1 = $money1 + 12.00;
                    $vollständigNameTicket1 = $resultTicket1['fullName'];
                    $nachNameTicket1 = $resultTicket1['lastName'];
                    $vorNameTicket1 = $resultTicket1['preName'];
                }else{
                    $money1 = $money1 + 15.00;
                }

                //DATEN TICKET NR.2
                $ageTicket2 = htmlspecialchars($conn->real_escape_string($_POST["ticketAge2"]));
                $emailTicket2 = htmlspecialchars($conn->real_escape_string($_POST["ticketEmail2"]));

                $nachNameTicket2 = htmlspecialchars($conn->real_escape_string($_POST["ticketName2"]));
                $vorNameTicket2 = htmlspecialchars($conn->real_escape_string($_POST["ticketVorName2"]));

                //KOMPLETTEN NAMEN ERSTELLEN
                $vollständigNameTicket2 = $vorNameTicket2 . " " . $nachNameTicket2;
                $resultTicket2 = checkNameKombinationOfMCG($vollständigNameTicket2);
                if($resultTicket2['found']){
                    $money2 = $money2 + 12.00;
                    $vollständigNameTicket2 = $resultTicket2['fullName'];
                    $nachNameTicket2 = $resultTicket2['lastName'];
                    $vorNameTicket2 = $resultTicket2['preName'];
                }else{
                    $money2 = $money2 + 15.00;
                }

                //PRÜFEN DER NAMEN AUF UNTERSCHIEDLICHKEIT
                if($vollständigNameTicket1 != $vollständigNameTicket2){

                    // SQL-Abfrage mit Platzhaltern für die Variablen
                    $isNameInDB = "SELECT COUNT(*) AS count FROM `tickets` WHERE `nachname` LIKE ? AND `vorname` LIKE ?";
                    // Die Abfrage vorbereiten
                    $stmt = $conn->prepare($isNameInDB);
                    $stmt->bind_param("ss", $nachNameTicket1, $vorNameTicket1);
                    //echo "DEBUG: Vorname Ticket 1: " . $vorNameTicket1 . "<br>";
                    //echo "DEBUG: Name Ticket 1: " . $nachNameTicket1 . "<br>";
                    // Abfrage ausführen
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if($row['count'] == 0){
                        $stmt->close();
                        $stmt = $conn->prepare($isNameInDB);
                        // GO ON WITH CHECK TICKET 2
                        $stmt->bind_param("ss", $nachNameTicket2, $vorNameTicket2);
                        //echo "DEBUG: Vorname Ticket 2: " . $vorNameTicket2 . "<br>";
                        //echo "DEBUG: Name Ticket 2: " . $nachNameTicket2 . "<br>";
                        // Abfrage ausführen
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        if($row['count'] == 0){
                            // GO ON WITH OTHER CODE

                            //if(checkCustomerIfExists($emailKäufer) != true){
                            //  writeCustomer($vorNameKäufer, $nachNameKäufer, $emailKäufer, $ageKäufer, $telNummerKäufer, $klasseKäufer);
                            //}
                            //
                            //$customerId_DB = getCustomerId($emailKäufer);
                            //writeTicket();
                        }else{
                            // NAME FOR TICKET 2 IS ALREADY IN USE
                            die("NAME FOR TICKET 2 IS ALREADY IN USE");
                        }
                    }else{
                        // NAME FOR TICKET 1 IS ALREADY IN USE
                        die("NAME FOR TICKET 1 IS ALREADY IN USE");
                    }
                }else{
                    //NAMEN DER BEIDEN TICKETS SIND GLEICH ODER EIN ANDERER UNBEKANNTER FEHLER IST AUFGETRETEN
                    echo "DEBUG: Namen sind nicht unterschiedlich" . "<br>";
                    echo "die";
                    die;
                }

            }else{

                //DIESER BLOCK WIRD AUSGEFÜHRT, WENN DER KÄUFER NUR EIN TICKET KAUFT
                //DATEN TICKET
                $ageTicket = htmlspecialchars($conn->real_escape_string($_POST["ticketAge1"]));
                $emailTicket = htmlspecialchars($conn->real_escape_string($_POST["ticketEmail1"]));

                $nachNameTicket = htmlspecialchars($conn->real_escape_string($_POST["ticketName1"]));
                $vorNameTicket = htmlspecialchars($conn->real_escape_string($_POST["ticketVorName1"]));
                //KOMPLETTEN NAMEN ERSTELLEN
                $vollständigNameTicket = $vorNameTicket . " " . $nachNameTicket;

                //GEHÖRT ZUM MCG?
                $resultTicket = checkNameKombinationOfMCG($vollständigNameTicket);
                if($resultTicket['found']){
                    $money = $money + 12.00;
                    $vollständigNameTicket = $resultTicket['fullName'];
                    $nachNameTicket = $resultTicket['lastName'];
                    $vorNameTicket = $resultTicket['preName'];

                    echo $vorNameTicket . "<br>";
                    echo $nachNameTicket . "<br>";

                    //PRÜFEN, OB TICKET SCHON AUF DIESEN NAMEN AUSGESTELLT WURDE
                    //GET KÄUFER ID 
                    //WRITE TICKET
                }else{
                    $money = $money + 15.00;
                    //PRÜFEN, OB TICKET SCHON AUF DIESEN NAMEN AUSGESTELLT WURDE
                    //checkIfNameOfTicketAlreadyExists($conn,$nachNameTicket,$vorNameTicket);
                }
            }
            echo "Der Preis für deine Tickets beträgt " . $money + $money1 + $money2 . ".00€";
        }

        
        //PRÜFEN, OB EINGETRAGENE PERSON ZUGEHÖRIG ZUM MCG IST -> MITHILFE NAMENSLISTE SPORTFEST
        //WENN EXTERN -> 15€; WENN INTERN -> 12€

        function checkNameKombinationOfMCG($name) {
            // Pfad zur CSV-Datei
            $csvFile = 'data/Namen+16.csv';
            
            // Datei öffnen und Zeile für Zeile lesen
            if (($handle = fopen($csvFile, "r")) !== false) {
                // Erste Zeile (Header) überspringen
                fgetcsv($handle); // Header-Zeile überspringen
        
                // Den gesuchten Namen aufteilen in Vorname und Nachname
                $nameParts = explode(" ", trim($name));
                $suchNachname = array_pop($nameParts); // Der letzte Teil ist der Nachname
                $suchVorname = implode(" ", $nameParts); // Der Rest ist der Vorname
            
                // Durch alle Zeilen der CSV-Datei iterieren
                while (($data = fgetcsv($handle, 1000, ",")) !== false) { // Komma als Trennzeichen
        
                    // Sicherstellen, dass beide Spalten vorhanden sind
                    $nachname = isset($data[0]) ? trim(str_replace(',', '', $data[0])) : '';
                    $vorname = isset($data[1]) ? trim(str_replace(',', '', $data[1])) : '';
        
                    // Format für den gesamten Namen "Vorname Nachname"
                    $fullName = $vorname . " " . $nachname;
        
                    // Prüfen, ob der gesamte Name übereinstimmt
                    if (strcasecmp($fullName, $name) === 0) {
                        fclose($handle); // Datei schließen
                        return ['found' => true, 'fullName' => $fullName, 'preName' => $vorname, 'lastName' => $nachname];
                    }
        
                    // Die Vornamen aufteilen und prüfen, ob einer übereinstimmt
                    $vornamenArray = explode(" ", $vorname);
        
                    // Überprüfen, ob der gesuchte Vorname mit einem der Vornamen übereinstimmt
                    foreach ($vornamenArray as $vname) {
                        if (strcasecmp($vname, $suchVorname) === 0 && strcasecmp($nachname, $suchNachname) === 0) {
                            fclose($handle); // Datei schließen
                            return ['found' => true, 'fullName' => $fullName, 'preName' => $vorname, 'lastName' => $nachname];
                        }
                    }
                }
                fclose($handle); // Datei schließen
            }
            return ['found' => false, 'fullName' => '', 'preName' => '', 'lastName' => ''];
        }

        function checkIfNameOfTicketAlreadyExists($conn,$nachNameTicket,$vorNameTicket){
            // SQL-Abfrage mit Platzhaltern für die Variablen
            $isNameInDB = "SELECT COUNT(*) AS count FROM `tickets` WHERE `nachname` LIKE ? AND `vorname` LIKE ?";
            // Die Abfrage vorbereiten
            $stmt = $conn->prepare($isNameInDB);
            $stmt->bind_param("ss", $nachNameTicket, $vorNameTicket);
            //echo "DEBUG: Vorname Ticket 1: " . $vorNameTicket1 . "<br>";
            //echo "DEBUG: Name Ticket 1: " . $nachNameTicket1 . "<br>";
            // Abfrage ausführen
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if($row['count'] == 0){
                if(checkCustomerIfExists($emailKäufer)){
                    //writeCustomer($vorNameKäufer, $nachNameKäufer, $emailKäufer, $ageKäufer, $telNummerKäufer, $klasseKäufer);
                    echo "Käufer würde erstellt werden <br>";
                }else{
                    echo "Wir landen hier";
                }

                //$customerId_DB = getCustomerId($emailKäufer);
                //writeTicket();
            }else{
                echo "Name für dieses Ticket wird schon benutzt <br>";
            }
        }

        function checkCustomerIfExists($email){
            // SQL-Abfrage mit Platzhaltern für die Variablen
            $doesCustomerExistsAlready = "SELECT COUNT(*) AS count FROM `käufer` WHERE `email` LIKE ?";
            // Die Abfrage vorbereiten
            $stmt = $conn->prepare($doesCustomerExistsAlready);
            $stmt->bind_param("s", $email);
            // Abfrage ausführen
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if($row['count'] == 0){
                return false;
                echo "Käufer existiert schon <br>";
            }else{
                return true;
            }
        }

        function writeCustomer($customer_preName, $customer_lastName, $customer_email, $customer_age, $customer_telNr, $customer_claas){
            $sql = "INSERT INTO `käufer` (`vorname`,`nachname`,`email`,`age`,`telNr`,`klasse`) VALUES (?,?,?,?,?,?)";
        }

        function writeTicket($nachName_DB, $vorName_DB, $email_DB, $age_DB, $sum_DB, $käuferId_DB){

        }

        //PRÜFEN, OB PERSON, FÜR DIE EIN TICKET AUSGESTELLT WERDEN SOLL, SCHON EIN TICKET HAT
        //WENN TICKET SCHON VORHANDEN = X - CONFLICT

        //**TODO**: TABELLE BRAUCHT NOCH EINE SPALTE FÜR DIE KOSTEN DES TICKETS

        //WENN CHECKS = SUCCESFULL -> EINTRAGEN DER DATEN
        
    ?>
</body>
</html>