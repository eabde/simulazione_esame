<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');
    require_once("../database/database.php");

    $response = array();

    try {
        if(isset($_GET['id'])) {
            $stazioneId = $_GET['id'];
        
            $sql = "DELETE FROM stazioni WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $stazioneId);
            
            if($stmt->execute()) {
                $response['status'] = "ok";
                $response['message'] = "Stazione eliminata con successo";
            } else {
                $response['status'] = "error";
                $response['message'] = "Stazione non trovata o errore nell'eliminazione: " . $stmt->error;
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "ID stazione non fornito";
        }

        $stmt->close();

    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    $conn->close();
?>
