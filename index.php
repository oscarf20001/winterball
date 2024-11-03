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

?>

<!DOCTYPE html>
<html lang="de">
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
                    <input type="text" id="name" name="nachname" required>
                    <label for="nachname">Dein Nachname:<sup>*</sup></label>
                </div>
                <div class="input-field vorname">
                    <input type="text" id="vorname" name="vorname" required>
                    <label for="vorname">Dein Vorname:<sup>*</sup></label>
                </div>
                <div class="input-field email">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Deine Email-Adresse:<sup>*</sup></label>
                </div>
                <div class="input-field telNumber">
                    <input type="tel" id="telNumber" name="telNumber" required>
                    <label for="telNumber">Deine Telefonnummer:<sup>*</sup></label>
                </div>
                <div class="input-field age">
                    <input type="number" id="age" name="age" required>
                    <label for="age">Dein Alter:<sup>* Zum Zeitpunkt des Balls</sup></label>
                </div>
                <div class="input-field klasse">
                    <input type="text" id="klasse" name="klasse">
                    <label for="klasse">Deine Klasse:<sup>*</sup> (Format: Klassenstufe/Zug)</label>
                </div>
                <div class="input-field cntTickets">
                    <input type="number" id="cntTickets" name="cntTickets" min="1" max="2" required>
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
                <h3 id="headlineTicket01">Ticket Nr. <span>1</span></h3>

                <div class="input-field ticketName">
                    <input type="text" name="ticketName" required>
                    <label for="ticketName">Nachame:<sup>*</sup></label>
                </div>
                <div class="input-field ticketVorName">
                    <input type="text" id="" name="ticketVorName" required>
                    <label for="ticketVorName">Vorname:<sup>*</sup></label>
                </div>
                <div class="input-field ticketEmail">
                    <input type="email" id="" name="ticketEmail" required>
                    <label for="ticketEmail">Email:<sup>*</sup></label>
                </div>
                <div class="input-field ticketAge">
                    <input type="text" id="" name="ticketAge" required>
                    <label for="ticketAge">Alter:<sup>* Zum Zeitpunkt des Balls</sup></label>
                </div>
            </div>
        </div>

        <input type="button" value="Daten überprüfen!" id="checkData">
        
    </form>
    <div class="upperCheck" id="check" style="display:none;">
        <div class="check">
            <h1 id="HeadlineCheck">Bitte überprüfen Sie ihre eingegebenen Daten:</h1>
            <div class="checkKäufer">
                <div class="left">
                    <h2>Käufer:</h2>
                </div>
                <div class="middle">
                    <p>Nachname:</p>
                    <p>Vorname:</p>
                    <p>Email:</p>
                    <p>Telefonnummer:</p>
                    <p>Alter:</p>
                    <p id="checkKäuferClaas">Klasse (wenn Schüler des MCG):</p>
                    <p>Anzahl Tickets:</p>
                </div>
                <div class="right">
                    <p id="lastname0"></p>
                    <p id="prename0"></p>
                    <p id="mail0"></p>
                    <p id="telNr0"></p>
                    <p id="age0"></p>
                    <p id="claas0"></p>
                    <p id="count0"></p>
                </div>
            </div>
            <div class="checkTicket01">
                <div class="left">
                    <h2>Ticket 1:</h2>
                </div>
                <div class="middle">
                    <p>Nachname:</p>
                    <p>Vorname:</p>
                    <p>Email:</p>
                    <p>Alter:</p>
                </div>
                <div class="right">
                    <p id="lastnameCheck01"></p>
                    <p id="prenameCheck01"></p>
                    <p id="mailCheck01"></p>
                    <p id="ageCheck01"></p>
                </div>
            </div>
            <div class="checkTicket02" id="checkTicket02" style="display:;">
                <div class="left">
                    <h2>Ticket 2:</h2>
                </div>
                <div class="middle">
                    <p>Nachname:</p>
                    <p>Vorname:</p>
                    <p>Email:</p>
                    <p>Alter:</p>
                </div>
                <div class="right">
                    <p id="lastnameCheck02"></p>
                    <p id="prenameCheck02"></p>
                    <p id="mailCheck02"></p>
                    <p id="ageCheck02"></p>
                </div>
            </div>
            <div class="moneyBox">
                <div class="left">
                    <h2>Gesamtsumme</h2>
                </div>
                <div class="middle">
                    <p id="moneyTicket01">Ticket 1:</p>
                    <p id="moneyTicket02">Ticket 2:</p>
                    <p id="moneyBoxSum">Summe:</p>
                </div>
                <div class="right"></div>
            </div>
            <div class="buttons">
                <input type="button" id="manipulateData" value="Daten korrigieren!">
                <input type="submit" id="sendData" value="Daten absenden!" form="main">
            </div>
        </div>
    </div>

    <div id="disclaimer">
        <h1>Wichtige Info:</h1>
        <div class="notePoints">
            <p>- Der Käufer steht für seine gekauften Tickets in der Veranwortung</p>
            <p>- Keine Anmeldung unter 16 Jahren</p>
            <p>- Zahlung in Bar an Raphael Stark oder Oscar Streich persönlich (Zeitpunkt für Bezahlung wird noch bekannt gegeben)</p>
            <br>
            <p>- Durch klicken auf "OK" erklären Sie sich einverstanden mit oben genannten Punkten</p>
        </div>
        <input type="button" value="OK" onclick="document.getElementById('disclaimer').style.display = 'none';">
    </div>

    <script>
        // Sicherstellen, dass das DOM vollständig geladen ist
        document.addEventListener("DOMContentLoaded", function() {
            const cntTicketsInput = document.getElementById("cntTickets");
            const ticketsContainer = document.getElementById("ticketsContainer");
            const ticketCountDisplay = document.getElementById("ticketCountDisplay");

            document.getElementById("manipulateData").style.display = "none";
            document.getElementById("sendData").style.display = "none";

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
                            <h3 id="headlineTicket0${i}">Ticket Nr. <span>${i}</span></h3>
                            <div class="input-field ticketName">
                                <input type="text" id="name0${i}" name="ticketName${i}" required>
                                <label for="ticketName${i}">Nachame:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketVorName">
                                <input type="text" id="prename0${i}" name="ticketVorName${i}" required>
                                <label for="ticketVorName${i}">Vorname:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketEmail">
                                <input type="email" id="mail0${i}" name="ticketEmail${i}" required>
                                <label for="ticketEmail${i}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" id="age0${i}" name="ticketAge${i}" required>
                                <label for="ticketAge${i}">Alter:<sup>* Zum Zeitpunkt des Balls</sup></label>
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
                            <h3 id="headlineTicket01">Ticket Nr. <span>${count}</span></h3>
                            <div class="input-field ticketName">
                                <input type="text" id="name01" name="ticketName${count}" required>
                                <label for="ticketName${count}">Nachame:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketVorName">
                                <input type="text" id="prename01" name="ticketVorName${count}" required>
                                <label for="ticketVorName${count}">Vorname:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketEmail">
                                <input type="email" id="mail01" name="ticketEmail${count}" required>
                                <label for="ticketEmail${count}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" id="age01" name="ticketAge${count}" required>
                                <label for="ticketAge${count}">Alter:<sup>* Zum Zeitpunkt des Balls</sup></label>
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

        document.getElementById("checkData").addEventListener('click', async function() {
            document.getElementById("check").style.display = "flex";
            let gesSumme = 0;

            // Käuferinformationen festlegen
            document.getElementById("lastname0").innerText = document.getElementById("name").value;
            document.getElementById("prename0").innerText = document.getElementById("vorname").value;
            document.getElementById("mail0").innerText = document.getElementById("email").value;
            document.getElementById("telNr0").innerText = document.getElementById("telNumber").value;
            document.getElementById("age0").innerText = document.getElementById("age").value;
            document.getElementById("claas0").innerText = document.getElementById("klasse").value;
            document.getElementById("count0").innerText = document.getElementById("cntTickets").value;

            // Ticket 01 Informationen festlegen
            document.getElementById("lastnameCheck01").innerText = document.getElementById("name01").value;
            document.getElementById("prenameCheck01").innerText = document.getElementById("prename01").value;
            document.getElementById("mailCheck01").innerText = document.getElementById("mail01").value;
            document.getElementById("ageCheck01").innerText = document.getElementById("age01").value;

            const ticketCount = parseInt(document.getElementById("cntTickets").value);

            if (ticketCount === 2) {
                // Ticket 02 anzeigen 
                document.getElementById("moneyTicket02").style.display = "flex";

                if(window.innerWidth <= 768){
                    document.getElementById("checkTicket02").style.display = "grid";
                }else{
                    document.getElementById("checkTicket02").style.display = "flex";
                }
                
                // Ticket 02 Infos festlegen
                document.getElementById("lastnameCheck02").innerText = document.getElementById("name02")?.value || '';
                document.getElementById("prenameCheck02").innerText = document.getElementById("prename02")?.value || '';
                document.getElementById("mailCheck02").innerText = document.getElementById("mail02")?.value || '';
                document.getElementById("ageCheck02").innerText = document.getElementById("age02")?.value || '';

                // Preise abrufen
                const price01 = await getTicketPrice(
                    document.getElementById("prename01").value.trim(), 
                    document.getElementById("name01").value.trim()
                );

                const price02 = await getTicketPrice(
                    document.getElementById("prename02").value.trim(),
                    document.getElementById("name02").value.trim()
                );

                const gesSumme = price01 + price02;
                document.getElementById("moneyTicket01").innerHTML = "Ticket 1: " + price01 + "€";
                document.getElementById("moneyTicket02").innerHTML = "Ticket 2: " + price02 + "€";
                document.getElementById("moneyBoxSum").innerText = "Summe: " + gesSumme + "€";
            } else {
                // Ticket 02 ausblenden
                document.getElementById("checkTicket02").style.display = "none";
                document.getElementById("moneyTicket02").style.display = "none";

                const price01 = await getTicketPrice(
                    document.getElementById("prename01").value.trim(), 
                    document.getElementById("name01").value.trim()
                );

                document.getElementById("moneyTicket01").innerHTML = "Ticket 1: " + price01 + "€";
                document.getElementById("moneyBoxSum").innerText = "Summe: " + price01 + "€";
            }

            document.getElementById("manipulateData").style.display = "flex";
            document.getElementById("sendData").style.display = "flex";
        });

        if(window.innerWidth <= 768){
            document.getElementById('HeadlineCheck').innerHTML = "Bitte überprüfen:"
            document.getElementById('checkKäuferClaas').innerHTML = "Klasse"
        }else{
            document.getElementById('HeadlineCheck').innerHTML = "Bitte überprüfen Sie ihre eingegebenen Daten:"
            document.getElementById('checkKäuferClaas').innerHTML = "Klasse (wenn Schüler des MCG):"
        }


        document.getElementById("manipulateData").addEventListener('click', function(){
            document.getElementById("check").style.display = "none";
        })

        document.getElementById("sendData").addEventListener('click', function(){
            document.getElementById("check").style.display = "none";
        })

        function getTicketPrice(prename, lastname) {
            // Hole die Werte der Eingabefelder und kombiniere sie zum vollständigen Namen
            const fullName = prename + " " + lastname;

            // Sende eine POST-Anfrage an die PHP-Datei, um den Preis abzurufen
            return fetch("getTicketPrice.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "fullName=" + encodeURIComponent(fullName)  // Den vollständigen Namen als Parameter senden
            })
            .then(response => response.text())  // Die Antwort als Text empfangen
            .then(data => {
                // Konvertiere die Antwort in einen Integer
                const price = parseInt(data, 10);
                
                // Überprüfe, ob die Konvertierung erfolgreich war, sonst gebe eine Fehlermeldung aus
                if (isNaN(price)) {
                    //console.error('Fehler: Der Rückgabewert ist kein gültiger Integer.', data);
                    return 0; // Wenn der Wert ungültig ist, null zurückgeben oder eine andere Aktion ausführen
                }
                
                // Gib den Preis als Integer zurück
                return price;
            })
            .catch(error => {
                console.error('Fehler:', error);
                return null; // Bei Fehlern null zurückgeben oder eine andere Aktion ausführen
            });
        }


    </script>

    <?php

        //DATEN AUS DEM FORMULAR ABRUFEN
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //MAYBE MIT COOKIES ARBEITEN, UM DATEN DES KÄUFERS IM FORMULAR ZU SPEICHERN, DAMIT SIE NICHT VERLOREN GEHEN, WENN MAN ABSENDET UND ES EINEN ERROR GIBT? 

            //global $nachNameKäufer,$vorNameKäufer,$emailKäufer,$telNummerKäufer,$ageKäufer,$klasseKäufer,$countTicketsKäufer;
            //DATEN KÄUFER
                $nachNameKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["nachname"])));
                $vorNameKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["vorname"])));
                $emailKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["email"])));
                $telNummerKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["telNumber"])));
                $ageKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["age"])));
                $klasseKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["klasse"])));
                $countTicketsKäufer = htmlspecialchars($conn->real_escape_string(trim($_POST["cntTickets"])));
                
            //ANDERE VARIABLEN
                $money = 0.00;
                $money1 = 0.00;
                $money2 = 0.00;

            if($countTicketsKäufer == 2){
                
                //DIESER BLOCK WIRD AUSGEFÜHRT, WENN DER KÄUFER MEHR ALS EIN TICKET KAUFT
                //DATEN TICKET NR.1
                $ageTicket1 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketAge1"])));
                $emailTicket1 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketEmail1"])));

                $nachNameTicket1 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketName1"])));
                $vorNameTicket1 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketVorName1"])));

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
                $ageTicket2 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketAge2"])));
                $emailTicket2 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketEmail2"])));

                $nachNameTicket2 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketName2"])));
                $vorNameTicket2 = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketVorName2"])));

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
                            // WRITE BOOTH TICKETS
                            //TICKET 01: 
                            if (checkCustomerIfExists($emailKäufer)) {
                                //KUNDE EXISTIERT BEREITS
                                //ID abrufen
                                $customerId = getCustomerId($emailKäufer);
    
                                //TICKET SCHREIBEN
                                if(writeTicket($nachNameTicket1, $vorNameTicket1, $emailTicket1, $ageTicket1, $money1, $customerId)){
                                    //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                    sendMail($emailKäufer, $vorNameKäufer, $emailTicket1, $vorNameTicket1, true, $emailTicket2, $vorNameTicket2);
                                }else{
                                    echo "Ticket wurde nicht erstellt";
                                }
                            } else {
                                //Käufer existiert noch nicht. Käufer erstellen. Id abrufen
    
                                //CREATE CUSTOMER
                                $customerId = writeCustomer($vorNameKäufer,$nachNameKäufer,$emailKäufer,$ageKäufer,$telNummerKäufer,$klasseKäufer);
    
                                //TICKET AUF CUSTOMERID SCHREIBEN
                                if(writeTicket($nachNameTicket1, $vorNameTicket1, $emailTicket1, $ageTicket1, $money1, $customerId)){
                                    //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                    sendMail($emailKäufer, $vorNameKäufer, $emailTicket1, $vorNameTicket1, true, $emailTicket2, $vorNameTicket2);
                                }else{
                                    echo "Ticket wurde nicht erstellt";
                                }
                            }

                            //TICKET 02:
                            if (checkCustomerIfExists($emailKäufer)) {
                                //KUNDE EXISTIERT BEREITS
                                //ID abrufen
                                $customerId = getCustomerId($emailKäufer);
    
                                //TICKET SCHREIBEN
                                if(writeTicket($nachNameTicket2, $vorNameTicket2, $emailTicket2, $ageTicket2, $money2, $customerId)){
                                    //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                    sendMail($emailKäufer, $vorNameKäufer, $emailTicket1, $vorNameTicket1, true, $emailTicket2, $vorNameTicket2);
                                }else{
                                    echo "Ticket wurde nicht erstellt";
                                }
                            } else {
                                //Käufer existiert noch nicht. Käufer erstellen. Id abrufen
    
                                //CREATE CUSTOMER
                                $customerId = writeCustomer($vorNameKäufer,$nachNameKäufer,$emailKäufer,$ageKäufer,$telNummerKäufer,$klasseKäufer);
    
                                //TICKET AUF CUSTOMERID SCHREIBEN
                                if(writeTicket($nachNameTicket2, $vorNameTicket2, $emailTicket2, $ageTicket2, $money2, $customerId)){
                                    //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                    sendMail($emailKäufer, $vorNameKäufer, $emailTicket1, $vorNameTicket1, true, $emailTicket2, $vorNameTicket2);
                                }else{
                                    echo "Ticket wurde nicht erstellt";
                                }
                            }
                        }else{
                            // NAME FOR TICKET 2 IS ALREADY IN USE
                            // Beispielaufruf der Funktion, wenn der Name bereits verwendet wird
                            $nameInUse = true;
                            if ($nameInUse) {
                                showAlert("NAME FOR TICKET 2 ALREADY IN USE");
                                die;
                            }
                        }
                    }else{
                        // NAME FOR TICKET 1 IS ALREADY IN USE
                        $nameInUse = true;
                            if ($nameInUse) {
                                showAlert("NAME FOR TICKET 1 ALREADY IN USE");
                                die;
                            }
                    }
                }else{
                    //NAMEN DER BEIDEN TICKETS SIND GLEICH ODER EIN ANDERER UNBEKANNTER FEHLER IST AUFGETRETEN
                    $sameNames = true;
                    if ($sameNames) {
                        showAlert("NAMES ARENT DIFFERENT");
                        die;
                    }
                }

            }else{

                //DIESER BLOCK WIRD AUSGEFÜHRT, WENN DER KÄUFER NUR EIN TICKET KAUFT
                //DATEN TICKET
                $ageTicket = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketAge1"])));
                $emailTicket = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketEmail1"])));

                $nachNameTicket = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketName1"])));
                $vorNameTicket = htmlspecialchars($conn->real_escape_string(trim($_POST["ticketVorName1"])));
                //KOMPLETTEN NAMEN ERSTELLEN
                $vollständigNameTicket = $vorNameTicket . " " . $nachNameTicket;

                //GEHÖRT ZUM MCG?
                $resultTicket = checkNameKombinationOfMCG($vollständigNameTicket);
                if($resultTicket['found']){
                    $money = $money + 12.00;
                    $vollständigNameTicket = $resultTicket['fullName'];
                    $nachNameTicket = $resultTicket['lastName'];
                    $vorNameTicket = $resultTicket['preName'];

                    if(checkIfNameOfTicketAlreadyExists($nachNameTicket,$vorNameTicket)){
                        //TICKET EXISTIERT SCHON
                        $nameInUse = true;
                        if ($nameInUse) {
                            showAlert("NAME FOR TICKET ALREADY IN USE");
                            die;
                        }
                    }else{
                        //TICKET EXISTIERT NOCH NICHT
                        if (checkCustomerIfExists($emailKäufer)) {
                            //KUNDE EXISTIERT BEREITS
                            //ID abrufen
                            $customerId = getCustomerId($emailKäufer);

                            //TICKET SCHREIBEN
                            if(writeTicket($nachNameTicket, $vorNameTicket, $emailTicket, $ageTicket, $money, $customerId)){
                                //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                sendMail($emailKäufer, $vorNameKäufer, $emailTicket, $vorNameTicket, false, "", "");
                            }else{
                                echo "Ticket wurde nicht erstellt";
                            }
                        } else {
                            //Käufer existiert noch nicht. Käufer erstellen. Id abrufen

                            //CREATE CUSTOMER
                            $customerId = writeCustomer($vorNameKäufer,$nachNameKäufer,$emailKäufer,$ageKäufer,$telNummerKäufer,$klasseKäufer);

                            //TICKET AUF CUSTOMERID SCHREIBEN
                            if(writeTicket($nachNameTicket, $vorNameTicket, $emailTicket, $ageTicket, $money, $customerId)){
                                //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                sendMail($emailKäufer, $vorNameKäufer, $emailTicket, $vorNameTicket, false, "", "");
                            }else{
                                echo "Ticket wurde nicht erstellt";
                            }
                        }
                    }

                    //PRÜFEN, OB TICKET SCHON AUF DIESEN NAMEN AUSGESTELLT WURDE
                    //GET KÄUFER ID 
                    //WRITE TICKET
                }else{
                    $money = $money + 15.00;
                    //PRÜFEN, OB TICKET SCHON AUF DIESEN NAMEN AUSGESTELLT WURDE
                    if(checkIfNameOfTicketAlreadyExists($nachNameTicket,$vorNameTicket)){
                        //TICKET EXISTIERT SCHON
                        $nameInUse = true;
                        if ($nameInUse) {
                            showAlert("NAME FOR TICKET ALREADY IN USE");
                            die;
                        }
                    }else{
                        //TICKET EXISTIERT NOCH NICHT
                        if (checkCustomerIfExists($emailKäufer)) {
                            //KUNDE EXISTIERT BEREITS
                            //ID abrufen
                            $customerId = getCustomerId($emailKäufer);

                            //TICKET AUF CUSTOMERID SCHREIBEN
                            if(writeTicket($nachNameTicket, $vorNameTicket, $emailTicket, $ageTicket, $money, $customerId)){
                                //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                sendMail($emailKäufer, $vorNameKäufer, $emailTicket, $vorNameTicket, false, "", "");
                            }else{
                                echo "Ticket wurde nicht erstellt";
                            }
                        } else {
                            //Käufer existiert noch nicht. Käufer erstellen. Id abrufen

                            //CREATE KÄUFER
                            $customerId = writeCustomer($vorNameKäufer,$nachNameKäufer,$emailKäufer,$ageKäufer,$telNummerKäufer,$klasseKäufer);
                            
                            //TICKET AUF CUSTOMERID SCHREIBEN
                            if(writeTicket($nachNameTicket, $vorNameTicket, $emailTicket, $ageTicket, $money, $customerId)){
                                //EMAIL VERSENDEN: AN KÄUFER UND AN DIE, FÜR DIE TICKETS BESTELLT WURDEN
                                sendMail($emailKäufer, $vorNameKäufer, $emailTicket, $vorNameTicket, false, "", "");
                            }else{
                                echo "Ticket wurde nicht erstellt";
                            }
                        }
                    }
                }
            }
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
                            return ['found' => true, 'fullName' => $fullName, 'preName' => $vorname, 'lastName' => $nachname, 'money' => 12];
                        }
                    }
                }
                fclose($handle); // Datei schließen
            }
            return ['found' => false, 'fullName' => '', 'preName' => '', 'lastName' => '', 'money' => 15];
        }

        function checkCustomerIfExists($email){
            global $conn, $emailKäufer, $nachNameKäufer, $vorNameKäufer, $telNummerKäufer, $ageKäufer, $klasseKäufer, $countTicketsKäufer;
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

        function checkIfNameOfTicketAlreadyExists($nachNameTicket, $vorNameTicket) {
            global $conn, $emailKäufer, $nachNameKäufer, $vorNameKäufer, $telNummerKäufer, $ageKäufer, $klasseKäufer, $countTicketsKäufer;
            
            // SQL-Abfrage mit Platzhaltern für die Variablen
            $isTicketAlreadyInUse = "SELECT COUNT(*) AS count FROM `tickets` WHERE `nachname` LIKE ? AND `vorname` LIKE ?";
            
            // Die Abfrage vorbereiten
            $stmt = $conn->prepare($isTicketAlreadyInUse);
            if (!$stmt) {
                die("Fehler bei der Vorbereitung der SQL-Abfrage: " . $conn->error);
            }
            
            // Bindet die Parameter
            $stmt->bind_param("ss", $nachNameTicket, $vorNameTicket);
            
            // Abfrage ausführen
            if (!$stmt->execute()) {
                die("Fehler beim Ausführen der SQL-Abfrage: " . $stmt->error);
            }
            
            // Ergebnis abrufen
            $result = $stmt->get_result();
            
            $row = $result->fetch_assoc();
            
            if ($row['count'] == 0) {
                return false;
            } else {
                return true;
            }
        }

        function getCustomerId($email){
            global $conn, $emailKäufer, $nachNameKäufer, $vorNameKäufer, $telNummerKäufer, $ageKäufer, $klasseKäufer, $countTicketsKäufer;
            $sqlGetIdFromCustomerUsingEmail = "SELECT `ID` FROM `käufer` WHERE `email` = ?";

            $stmt = $conn->prepare($sqlGetIdFromCustomerUsingEmail);
            $stmt->bind_param("s",$email);
            $stmt->execute();

            $result = $stmt->get_result();
            $id = $result->fetch_assoc();

            return (int)$id['ID'];

            $stmt->close();
        }

        function writeCustomer($customer_preName, $customer_lastName, $customer_email, $customer_age, $customer_telNr, $customer_claas){
            global $conn, $emailKäufer, $nachNameKäufer, $vorNameKäufer, $telNummerKäufer, $ageKäufer, $klasseKäufer, $countTicketsKäufer;
            $sqlWriteNewCustomer = "INSERT INTO `käufer` (`vorname`,`nachname`,`email`,`age`,`telNr`,`klasse`) VALUES (?,?,?,?,?,?)";

            $stmt = $conn->prepare($sqlWriteNewCustomer);
            //KÄUFER MIT DEN ÜBERGEBENEN VARIABLEN IN DIE DATENBANK SCHREIBEN
            $stmt->bind_param("sssiss",$customer_preName, $customer_lastName, $customer_email, $customer_age, $customer_telNr, $customer_claas);
            $stmt->execute();

            //NACH ERSTELLEN DES KÄUFERS IN DER DATENBANK WIRD SOFORT DANACH SEINE ID ABGERUFEN UND RETURNED
            return getCustomerId($customer_email);

            $stmt->close();
        }

        function writeTicket($nachName_DB, $vorName_DB, $email_DB, $age_DB, $sum_DB, $käuferId_DB){
            global $conn, $emailKäufer, $nachNameKäufer, $vorNameKäufer, $telNummerKäufer, $ageKäufer, $klasseKäufer, $countTicketsKäufer;

            $sqlWriteNewTicket = "INSERT INTO `tickets` (`nachname`,`vorname`,`email`,`age`,`sum`,`käufer_ID`) VALUES (?,?,?,?,?,?)";

            $stmt = $conn->prepare($sqlWriteNewTicket);
            $stmt->bind_param("sssiii",$nachName_DB, $vorName_DB, $email_DB, $age_DB, $sum_DB, $käuferId_DB);
            if($stmt->execute()){
                return true;
            }else{
                die("Fehler beim Ausführen der SQL-Abfrage: " . $stmt->error);
            }
        }

        function showAlert($message) {
            echo '
            <div class="alerts" id="alerts">
                <div class="contentAlerts">
                    <h1>' . htmlspecialchars($message) . '</h1>
                    <input type="button" value="Okay, ich werde die Namen abändern" id="alertButton">
                </div>
            </div>
            <script>
                document.getElementById("alertButton").addEventListener("click", function() {
                    document.getElementById("alerts").style.display = "none";
                });
            </script>
            ';
        }

        function logMessage($message) {
            $logfile = 'mail_log.txt'; // Pfad zur Log-Datei
            file_put_contents($logfile, date("Y-m-d H:i:s") . " - " . $message . PHP_EOL, FILE_APPEND);
        }
        
        function sendMail($emailKäufer, $nameKäufer, $ticket1, $nameTicket1, $optionalTicket2, $ticket2 = null, $nameTicket2 = null) {
            $betreff = "Buchungsbestätigung - Winterball des MCGs 2024";
        
            // E-Mail-Header
            $header = "From: noreply@curiegymnasium.de\r\n";
            $header .= "Reply-To: streiosc@curiegym.de\r\n";
        
            // Nachrichtentext der Buchungsbestätigung an den Käufer
            $nachricht = "Hallo ".trim($nameKäufer).",\n\n";
            $nachricht .= "Vielen Dank für deine Buchung! Wir bestätigen hiermit, dass deine Anfrage eingegangen ist.\n";
            $nachricht .= "Wir melden uns bei dir, falls weitere Informationen benötigt werden.\n\n";
            $nachricht .= "Zusätzlich haben wir die Buchungsbestätigung an die angegebene(n) E-Mail-Adresse(n) des Tickets/der Tickets versendet.\n\n";
            $nachricht .= "Mit freundlichen Grüßen,\nGordon :)";
        
            // E-Mail an den Käufer senden
            if (mail($emailKäufer, $betreff, $nachricht, $header)) {
                logMessage("Email an Käufer ($emailKäufer) versendet");
            } else {
                logMessage("Fehler: Emailversand an Käufer ($emailKäufer) fehlgeschlagen");
            }
        
            // Nachricht für Ticket 1
            $nachrichtTicket1 = "Hallo ".trim($nameTicket1).",\n\n";
            $nachrichtTicket1 .= "Wir haben festgestellt, dass auf diese Email-Adresse ($ticket1) ein Ticket für den Winterball des MCGs 2024 gebucht wurde.\n\n";
            $nachrichtTicket1 .= "Falls das korrekt ist, brauchst du nichts weiter zu unternehmen.\n\n";
            $nachrichtTicket1 .= "Falls das NICHT korrekt ist, antworte bitte auf diese Email und teile uns das Problem mit.\n\n";
            $nachrichtTicket1 .= "Mit freundlichen Grüßen,\nGordon :)";
        
            // E-Mail an Ticket 1 senden
            if (mail($ticket1, $betreff, $nachrichtTicket1, $header)) {
                logMessage("Email an Ticket 1 ($ticket1) versendet");
            } else {
                logMessage("Fehler: Emailversand an Ticket 1 ($ticket1) fehlgeschlagen");
            }
        
            // Falls optionalTicket2 angegeben ist, Nachricht für Ticket 2 erstellen und senden
            if ($optionalTicket2 && $ticket2 !== null && $nameTicket2 !== null) {
                $nachrichtTicket2 = "Hallo ".trim($nameTicket2).",\n\n";
                $nachrichtTicket2 .= "Wir haben festgestellt, dass auf diese Email-Adresse ($ticket2) ein Ticket für den Winterball des MCGs 2024 gebucht wurde.\n\n";
                $nachrichtTicket2 .= "Falls das korrekt ist, brauchst du nichts weiter zu unternehmen.\n\n";
                $nachrichtTicket2 .= "Falls das NICHT korrekt ist, antworte bitte auf diese Email und teile uns das Problem mit.\n\n";
                $nachrichtTicket2 .= "Mit freundlichen Grüßen,\nGordon :)";
        
                // E-Mail an Ticket 2 senden
                if (mail($ticket2, $betreff, $nachrichtTicket2, $header)) {
                    logMessage("Email an Ticket 2 ($ticket2) versendet");
                } else {
                    logMessage("Fehler: Emailversand an Ticket 2 ($ticket2) fehlgeschlagen");
                }
            }
        }
        
        //**TODO**: TABELLE BRAUCHT NOCH EINE SPALTE FÜR DEN STATUS EINER BEZAHLUNG EINES TICKETS
        //**TODO**: ADMIN-PANEL
        
    ?>
</body>
</html>