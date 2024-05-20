<?php
    require_once("../database/database.php");

    $numSlot = $_POST['numSlot'];
    $numBiciclette = $_POST['numBiciclette'];
    $via = $_POST['via'];
    $provincia = $_POST['provincia'];
    $citta = $_POST['citta'];
    $regione = $_POST['regione'];

    $sql = "INSERT INTO stazioni (numSlot, numBiciclette, via, città, provincia, regione) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissss', $numSlot, $numBiciclette, $via, $citta, $provincia, $regione);

    $response = array();

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Stazione creata con successo.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Errore durante la creazione della stazione: ' . $stmt->error;
    }


    echo json_encode($response);

    $stmt->close();
    $conn->close();
?>
