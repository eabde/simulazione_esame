<?php
    require_once("../../database/database.php");

    $response = array();

    try {
        $id = $_GET['id'];
 
        $stmt = $conn->prepare("UPDATE biciclette SET distanzaPercorsa = 0 WHERE ID = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            $response['status'] = "success";
            $response['message'] = "Bicicletta sistemata";
        } else {
            $response['status'] = "error";
            $response['message'] = "Errore";
        }
    
        $stmt->close();
    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
    $conn->close();
?>
