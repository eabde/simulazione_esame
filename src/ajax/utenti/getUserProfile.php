<?php
header('Content-Type: application/json');
require_once("../../database/database.php");

session_start();

$response = array();

try {
    $IdUtente = $_SESSION["IdUtente"];
    
    $sql = "SELECT * FROM utenti WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $IdUtente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response['status'] = "ok";
        $response['data'] = $user;
    } else {
        $response['status'] = "error";
        $response['message'] = "Utente non trovato.";
    }

    $stmt->close();
} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
