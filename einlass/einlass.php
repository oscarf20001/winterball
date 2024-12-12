<?php
require dirname(__DIR__) . '/vendor/autoload.php';
include '../db_connection.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einlass</title>
</head>
<body>
    <h1>Einlass</h1>
</body>
</html>

<?php

//Ist Einlass überhaupt geöffnet? Nur wenn Einlass geöffnet ist, dann darf die Aktion durchgeführt werden
$sqlIsTicketingOpen = "SELECT status FROM controlls WHERE id = 1;";
$stmt = $conn->perpare($sqlIsTicketingOpen);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$status = $row['status'];

?>