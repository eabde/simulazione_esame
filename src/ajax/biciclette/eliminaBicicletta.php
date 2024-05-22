<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');
    require_once("../../database/database.php");

    $response = array();

    try {
        if(isset($_GET['id'])) {
            $biciclettaId = $_GET['id'];
        
            $sql = "DELETE FROM biciclette WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $biciclettaId);
            
            if($stmt->execute()) {
                $response['status'] = "ok";
                $response['message'] = "Bicicletta eliminata con successo";
            } else {
                $response['status'] = "error";
                $response['message'] = "Bicicletta non trovata o errore nell'eliminazione: " . $stmt->error;
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "ID bici non fornito";
        }

        $stmt->close();

    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    $conn->close();
?>
