<?php

function sendMail($käufer, $nameKäufer, $ticket1, $nameTicket1, $optionalTicket2, $ticket2, $nameTicket2){
    //$name = htmlspecialchars(trim($_POST["name"]));
    //$email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

    $betreff = "Buchungsbestätigung - Winterball des MCGs 2024";

    // E-Mail-Header
    $header = "From: noreply@curiegymnasium.de\r\n";  // Absender-Adresse
    $header .= "Reply-To: streiosc@curiegym.de\r\n";  // Antwort-Adresse

    // Nachrichtentext der Buchungsbestätigung
    $nachricht = "Hallo $nameKäufer,\n\n";
    $nachricht .= "Vielen Dank für deine Buchung! Wir bestätigen hiermit, dass deine Anfrage eingegangen ist.\n";
    $nachricht .= "Wir melden uns bei dir, falls weitere Informationen benötigt werden.\n\n";
    $nachricht .= "Zusätzlich haben wir die Buchungsbestätigung an deine angebenen Emails des Tickets/der Tickets versendet.\n\n";
    $nachricht .= "Mit freundlichen Grüßen,\n";
    $nachricht .= "Gordon :)";

    $nachrichtTicktet = "Hallo $nameTicket[$i],\n\n";
    $nachrichtTicktet .= "Wir haben festgestellt, dass auf diese Email-Adresse (".$ticket[$i].") ein Ticket für den Winterball des MCGs 2024 gebucht wurde.\n\n";
    $nachrichtTicktet .= "Falls das auch so sein sollte: .\n";
    $nachrichtTicktet .= "Du brauchst nichts weiter machen.\n\n";
    $nachrichtTicktet .= "Falls das NICHT so sein sollte: .\n";
    $nachrichtTicktet .= "Antworte bitte auf diese Email und teile uns das Problem mit.\n\n";
    $nachrichtTicktet .= "Mit freundlichen Grüßen,\n";
    $nachrichtTicktet .= "Gordon :)";
    
    if(!$optionalTicket2){
        //FALL, FÜR EIN TICKET (KÄUFER UND TICKET 1)
        $i = 1;

        if(mail($käufer, $betreff, $nachricht, $header)){
            echo "<script>console.log('Email an Käufer versendet')";
            echo "Email des Käufers: " . $käufer;
            echo "Name des Käufers: " . $nameKäufer;
            echo "Email des Tickets: " . $ticket[$i];
            echo "Name des Tickets: " . $nameTicket[$i];

            if(mail($ticket[$i], $betreff, $nachrichtTicket, $header)){
                echo "<script>console.log('Email an Ticket versendet')";

            }else{
                echo "<script>console.error('Email versandt an Ticket fehlgeschlagen')";
            }
        }else{
            echo "<script>console.error('Email versandt an Käufer fehlgeschlagen')";

        }
    }else{
        //FALL, FÜR ZWEI TICKET (KÄUFER UND TICKET 1 UND TICKET 2)
        if(mail($käufer, $betreff, $nachricht, $header)){
            echo "<script>console.log('Email an Käufer versendet')";
        }else{
            echo "<script>console.error('Emailversandt an Käufer fehlgeschlagen')";
        }

        for ($i = 1; $i < 3; $i++) { 
            if (mail($ticket[$i], $betreff, $nachricht, $header)) {
                echo "<script>console.log('Email an Ticket".[$i]." versendet')";
            }else{
                echo "<script>console.error('Emailversand an Ticket".[$i]." fehlgeschlagen')";
            }
        }                
    }
}