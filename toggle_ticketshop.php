<?php
// Verbindungsaufbau zur Datenbank
include 'db_connection.php';

// Überprüfen, ob eine POST-Anfrage mit der Aktion 'toggleOff' gesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOn') {
    try {
        // SQL-Query zur Aktualisierung der Datenbank
        $sqlToggleTicketShopOff = "UPDATE controlls SET status = 1 WHERE ID = 2";
        $stmt = $conn->prepare($sqlToggleTicketShopOff);
        $stmt->execute();

        // Erfolgsantwort als JSON zurückgeben
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Fehlerbehandlung: Fehler als JSON zurückgeben
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggleOff'){
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
} else {
    // Falls keine gültige Anfrage gesendet wurde
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn = null; // Datenbankverbindung schließen
?>
