<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use setasign\Fpdi\Fpdi;

try {
    $template = 'ticket_template.pdf';
    $names = ['Oscar', 'Marla', 'Vivi'];

    foreach ($names as $name) {
        $pdf = new Fpdi();
    
        // Lade das Template und importiere die erste Seite
        $pdf->setSourceFile($template);
        $tplIdx = $pdf->importPage(1);
    
        // Hole die Größe der importierten Seite
        $size = $pdf->getTemplateSize($tplIdx);
    
        // Füge eine neue Seite mit den exakten Maßen des Templates hinzu (aber ohne die explizite Seitenangabe)
        $pdf->AddPage(); // AddPage ohne Größe
    
        // Template auf der neuen Seite verwenden
        $pdf->useTemplate($tplIdx);
    
        // Text hinzufügen
        $pdf->SetFont('Helvetica', '', 16);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(10, $size['height'] - 20); // Text relativ zur Seitenhöhe positionieren
        $pdf->Write(10, 'Name: ' . $name);
    
        // Speichere das PDF
        $pdf->Output('F', __DIR__ . "/ticket_$name.pdf");
    }

} catch (Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
    print_r($e->getTraceAsString());
}


#02 - Name auf vorhandenes Template schreiben
#03 - QR-Code über externe Datei generieren
#04 - QR-Code per Mail verschicken
#05 - QR-Code in PDF einbauen 
#06 - PDF sichern