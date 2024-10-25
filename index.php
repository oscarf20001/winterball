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
                    <label for="age">Dein Alter:<sup>*</sup></label>
                </div>
                <div class="input-field klasse">
                    <input type="text" id="klasse" name="klasse" required>
                    <label for="klasse">Deine Klasse:<sup>*</sup> (Format: Klassenstufe/Zug)</label>
                </div>
                <div class="input-field cntTickets">
                    <input type="number" id="cntTickets" name="cntTickets" min="1" max="3" required>
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
                <div class="input-field ticketEmail">
                    <input type="email" id="" name="ticketEmail" required>
                    <label for="ticketEmail">Email:<sup>*</sup></label>
                </div>
                <div class="input-field ticketAge">
                    <input type="text" id="" name="ticketAge" required>
                    <label for="ticketAge">Alter:<sup>*</sup></label>
                </div>
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
                                <input type="email" name="ticketEmail${i}" required>
                                <label for="ticketEmail${i}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" name="ticketAge${i}" required>
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
                                <input type="email" name="ticketEmail${count}" required>
                                <label for="ticketEmail${count}">Email:<sup>*</sup></label>
                            </div>
                            <div class="input-field ticketAge">
                                <input type="text" name="ticketAge${count}" required>
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
</body>
</html>