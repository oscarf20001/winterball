<?php
require dirname(__DIR__) . '/vendor/autoload.php';
include '../db_connection.php';

//CHECK ALLOW CONNECTION AND COOKIE
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einlass</title>
    <link rel="stylesheet" href="../css/scanQR.css">
</head>
<body>
    <div class="outer" id="outer">
        <div class="header">
            <h1>Einlass-Controll-Panell</h1>
        </div>
        <div class="content">
            <div class="stats">
                <p>Status: 
                    <span id="statusText">
                        <?php

                        //Ist Einlass überhaupt geöffnet?
                        $sqlIsTicketingOpen = "SELECT status FROM controlls WHERE id = 1;";
                        $stmt = $conn->prepare($sqlIsTicketingOpen);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $status = $row['status'];
                        $stmt->close();

                        //Wenn dieser Wert null ist, bedeutet das, dass der Einlass geschlossen ist. Ein Scannen des QR-Codes ist somit nicht möglich
                        if($status == 0){
                            echo "<script>document.getElementById('statusText').innerText = 'geschlossen';</script>";
                            return; //Abbruch des Scriptes
                        }
                        echo "<script>document.getElementById('statusText').innerText = 'offen';</script>";

                        ?>
                    </span>
                </p>
                <div class="circleControll" id="circleStatusEinlass"></div>
            </div>
            <div class="containerTicket">
                <div class="vorname flex-element">
                    <p>Vorname: </p><span id="vornamme"></span>
                </div>
                <div class="name flex-element">
                    <p>Name: </p><span id="name"></span>
                </div>
                <div class="mail flex-element">
                    <p>Mail: </p><span id="mail"></span>
                </div>
                <div class="customer flex-element">
                    <p>Status Käufer: </p><span id="customer"></span>
                </div>
                <div class="zuschlag flex-element">
                    <p>Zuschlag: </p><span id="zuschlag"></span>
                </div>
            </div>
            <div class="eintrittCheck">
                <div class="zulassung flex-element">
                    <p>Einlass: </p><span id="einlass"></span>
                </div>
                <div class="reason flex-element">
                    <p>Grund: </p><span id="reason"></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'einlass') {
        //URL STRUCTURE: .../scanQrCode.php?action=einlass&name=Streich&prename=Oscar&mail=streiosc@curiegym.de
        $name = $_GET['name'];
        $prename = $_GET['prename'];
        $mail = $_GET['mail'];
        $booleanEinlass;
        $booleanZuschlag = False;
        $zuschlagB;

        //Dieser Code wird ausgeführt, wenn Einlass ($status == 1) => Einlass offen, QR-Codes dürfen gescannt werden
        //Ticket, für das gerade der Einlass angefordert wurde, finden
        $sqlSearchForTicket = "SELECT ID,QR_Einlass FROM tickets WHERE vorname LIKE ? AND nachname LIKE ? AND email LIKE ?;";
        $stmt = $conn->prepare($sqlSearchForTicket);
        $stmt->bind_param("sss", $prename, $name, $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $idTicketScannedQrCode = $row['ID'];
            $QR_Einlass = $row['QR_Einlass'];
        }
        $stmt->close();

        //Abbruch, wenn der Boolean von QR_Einlass ungleich 0 ist; 0 = nicht gescannt; 1 = gescannt
        if(!$QR_Einlass == 0){
            echo "<script>
                    document.getElementById('name').innerText = '" . strval($name) . "';
                    document.getElementById('vornamme').innerText = '" . strval($prename) . "';
                    document.getElementById('mail').innerText = '" . strval($mail) . "';
                    document.getElementById('customer').innerText = 'Bezahlt';
                    document.getElementById('zuschlag').innerText = '" . ($booleanZuschlag ? 'Ja' : 'Nein') . "';
                    document.getElementById('einlass').innerText = 'verweigert';
                    document.getElementById('einlass').classList.add('denied');
                    document.getElementById('einlass').classList.remove('allowed');
                    document.getElementById('reason').innerText = 'QR-Code schon gescannt!';
                </script>";
            $booleanEinlass = True;
            $zuschlagB = False;
            makeBg($booleanEinlass);
            return;
        }

        $booleanEinlass = False;

        //Prüfe, ob es eine ID für die gesuchte Person gibt
        if($idTicketScannedQrCode){
            //Prüfe, ob Käufer gepaid hat
            $zuschlagB = False;
            $booleanZuschlag = False;

            //Prüfe, ob Customer gezahlt hat
            if(checkPaidStatusCustomer($idTicketScannedQrCode,$conn) === 1){
                //Definiere Variablen für Zuschlag, falls nach 20:00 Uhr
                $zuschlag = 2.50;
                $checktimestamp = 1734116400;

                //UPDATE Table mit dieser ID => Set Einlass to 1 (QR-Code wurde gescannt!)
                $tsEinlass = time();
                $sqlUpdateTicket = "UPDATE tickets SET QR_Einlass = 1, ts_einlass = $tsEinlass WHERE ID = ?";
                $stmt = $conn->prepare($sqlUpdateTicket);
                $stmt->bind_param("i",$idTicketScannedQrCode);
                $stmt->execute();
                $stmt->close();

                //Prüfe, ob nach 20:00 Uhr
                if(time() >= $checktimestamp){
                    $zuschlagB = True;
                    $booleanZuschlag = True;
                    $sqlSetZuschlag = "UPDATE tickets SET zuschlag = 2.50 WHERE ID = ?";
                    $stmt = $conn->prepare($sqlSetZuschlag);
                    $stmt->bind_param("i",$idTicketScannedQrCode);
                    $stmt->execute();
                    $stmt->close();
                }

                buildUI($idTicketScannedQrCode,$name,$prename,$mail,$booleanEinlass, $zuschlagB);
                return;
            }
            echo "<script>
                    document.getElementById('name').innerText = '" . strval($name) . "';
                    document.getElementById('vornamme').innerText = '" . strval($prename) . "';
                    document.getElementById('mail').innerText = '" . strval($mail) . "';
                    document.getElementById('customer').innerText = 'Bezahlt';
                    document.getElementById('zuschlag').innerText = '" . ($booleanZuschlag ? 'Ja' : 'Nein') . "';
                    document.getElementById('einlass').innerText = 'verweigert';
                    document.getElementById('einlass').classList.add('denied');
                    document.getElementById('einlass').classList.remove('allowed');
                    document.getElementById('reason').innerText = 'Unbezahlte Tickets auf Käufer!';
                </script>";
            makeBg(true);
            return;
        }else{
            echo "Kein Ticket auf diese Daten gefunden";
            return;
        }

        //Daten zurückgeben an Einlass.php für UI
        //Wenn nach 20 Uhr => Zuschlag wurde gesetzt, weiterleitung auf andere PHP-Datei mit Login, um Zuschlag zu bestätigen

    } else {
        echo "Unbekannte Aktion!";
        return;
    }
} else {
    echo "Keine Aktion angegeben!";
    return;
}

function buildUI($id,$name,$vorname,$mail,$booleanEinlass,$booleanZuschlag){

    //Hintergrund Rot machen, wenn Boolean für Einlass = wahr
    makeBg($booleanEinlass);

    //Daten displayen; Button hinzufügen, wenn Zuschlag = wahr
    echo "<script>
                    document.getElementById('name').innerText = '" . strval($name) . "';
                    document.getElementById('vornamme').innerText = '" . strval($vorname) . "';
                    document.getElementById('mail').innerText = '" . strval($mail) . "';
                    document.getElementById('customer').innerText = 'Bezahlt';
                    document.getElementById('zuschlag').innerText = '" . ($booleanZuschlag ? 'Ja' : 'Nein') . "';
                    document.getElementById('einlass').innerText = 'genehmigt';
                    document.getElementById('einlass').classList.add('allowed');
                    document.getElementById('einlass').classList.remove('denied');
                    document.getElementById('reason').innerText = '-';
        </script>";
}

function makeBg($booleanEinlass){
    if(!$booleanEinlass){
        echo "<script>document.getElementById('outer').classList.add('bg-green')</script>";
        echo "<script>document.getElementById('outer').classList.remove('bg-red')</script>";
    }else{
        echo "<script>document.getElementById('outer').classList.add('bg-red')</script>";
        echo "<script>document.getElementById('outer').classList.remove('bg-green')</script>";
    }
}

function checkPaidStatusCustomer($idTicketScannedQrCode,$conn){
    //CustomerID
    $getCustomerIdOfTicket = "SELECT käufer_ID FROM tickets WHERE ID = ?";
    $stmt = $conn->prepare($getCustomerIdOfTicket);
    $stmt->bind_param("i",$idTicketScannedQrCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $customerID = $row['käufer_ID'];
    $stmt->close();

    //Check Status
    $checkStatusOfCustomerID = "SELECT status FROM käufer WHERE ID = ?";
    $stmt = $conn->prepare($checkStatusOfCustomerID);
    $stmt->bind_param("i",$customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $status = $row['status'];
    $stmt->close();

    if($status === 1){
        return 1;
    }else{
        return 0;
    }
}
?>