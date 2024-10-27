<?php

$name = "Johanna Schub";

if(checkNameKombinationOfMCG($name)){
    echo "Name wurde gefunden: " . $name;
}else{
    echo "Name wurde nicht gefunden: " . $name;
}

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
                return true; // Name gefunden
            }

            // Die Vornamen aufteilen und prüfen, ob einer übereinstimmt
            $vornamenArray = explode(" ", $vorname);

            // Überprüfen, ob der gesuchte Vorname mit einem der Vornamen übereinstimmt
            foreach ($vornamenArray as $vname) {
                if (strcasecmp($vname, $suchVorname) === 0 && strcasecmp($nachname, $suchNachname) === 0) {
                    fclose($handle); // Datei schließen
                    return true; // Name gefunden
                }
            }
        }
        fclose($handle); // Datei schließen
    }
    return false; // Name nicht gefunden
}


?>