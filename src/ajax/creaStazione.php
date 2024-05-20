<?php

    session_start();
    require_once("../database/database.php");



    $numSlot = $_POST['numSlot'];
    $numBiciclette = $_POST['numBiciclette'];
    $indirizzo = $_POST['indirizzo'];

    $sql = "INSERT INTO stazioni (numSlot, numBiciclette, indirizzo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $numSlot, $numBiciclette, $indirizzo);

    if ($stmt->execute()) {
        echo "Stazione creata con successo.";
    } else {
        echo "Errore durante la creazione della stazione.";
    }

    $stmt->close();
    $conn->close();
?>
