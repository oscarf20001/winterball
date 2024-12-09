<?php
header('Content-Type: application/json');
// Verbindungsaufbau zur Datenbank
include 'db_connection.php';

// Überprüfen, ob eine POST-Anfrage mit der Aktion 'toggleOff' gesendet wurde
// === ABFRAGE FÜR METHODE 2 = TICKETSHOP ===================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOn' && $_POST['service'] == 2) {
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleTicketShopOn = "UPDATE controlls SET status = 1 WHERE ID = 2";
        $stmt = $conn->prepare($sqlToggleTicketShopOn);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOff' && $_POST['service'] == 2){
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleTicketShopOff = "UPDATE controlls SET status = 0 WHERE ID = 2";
        $stmt = $conn->prepare($sqlToggleTicketShopOff);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

// === ABFRAGE FÜR METHODE 1 = EINLASS ===================================

}else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOn' && $_POST['service'] == 1){
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleEinlassOn = "UPDATE controlls SET status = 1 WHERE ID = 1";
        $stmt = $conn->prepare($sqlToggleEinlassOn);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOff' && $_POST['service'] == 1){
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleEinlassOff = "UPDATE controlls SET status = 0 WHERE ID = 1";
        $stmt = $conn->prepare($sqlToggleEinlassOff);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
// === ABFRAGE FÜR METHODE 3 = ABENDKASSE ===================================
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOn' && $_POST['service'] == 3){
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleAbendkasseOn = "UPDATE controlls SET status = 1 WHERE ID = 3";
        $stmt = $conn->prepare($sqlToggleAbendkasseOn);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOff' && $_POST['service'] == 3){
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleAbendkasseOff = "UPDATE controlls SET status = 0 WHERE ID = 3";
        $stmt = $conn->prepare($sqlToggleAbendkasseOff);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // Falls keine gültige Anfrage gesendet wurde
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn = null; // Datenbankverbindung schließen