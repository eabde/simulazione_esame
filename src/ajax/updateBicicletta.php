<?php
    require_once("../database/database.php");

    $response = array();

    try {
        $id = $_POST['id'];
        $codiceTag = $_POST['codiceTag'];
        $gps = $_POST['gps'];

        $stmt = $conn->prepare("UPDATE biciclette SET codiceTag = ?, gps = ? WHERE ID = ?");
        $stmt->bind_param('ssi', $codiceTag, $gps, $id);

        if ($stmt->execute()) {
            $response['status'] = "success";
            $response['message'] = "Bicicletta aggiornata con successo";
        } else {
            $response['status'] = "error";
            $response['message'] = "Errore durante l'aggiornamento della bicicletta. Riprova";
        }
    
        $stmt->close();
    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
    $conn->close();
?>
