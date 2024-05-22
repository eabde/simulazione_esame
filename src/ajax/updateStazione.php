<?php
    require_once("../database/database.php");

    $response = array();
    try {
        $id = $_POST['id'];
        $numSlot = $_POST['numSlot'];
        $numBiciclette = $_POST['numBiciclette'];
        $via = $_POST['via'];
        $citta = $_POST['citta'];
        $provincia = $_POST['provincia'];
        $regione = $_POST['regione'];

        $stmt = $conn->prepare("UPDATE stazioni SET numSlot=?, numBiciclette=?, via=?, città=?, provincia=?, regione=? WHERE ID=?");
        $stmt->bind_param('iissssi', $numSlot, $numBiciclette, $via, $citta, $provincia, $regione, $id);

        if ($stmt->execute()) {
            $response['status'] = "success";
            $response['message'] = "Stazione aggiornata con successo";
        } else {
            $response['status'] = "error";
            $response['message'] = "Errore durante l'aggiornamento della stazione. Riprova.";
        }
    
        $stmt->close();
    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
    $conn->close();
?>
