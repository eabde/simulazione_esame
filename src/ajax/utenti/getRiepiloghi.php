<?php
require_once("../../database/database.php");

header('Content-Type: application/json');
session_start();

$response = array();

if (isset($_SESSION['IdUtente'])) {
    $userId = $_SESSION['IdUtente']; 

    $query = "SELECT * FROM operazioni WHERE idUtente = ? ORDER BY data DESC, ora DESC";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $operations = $result->fetch_all(MYSQLI_ASSOC);

        $response['status'] = 'success';
        $response['data'] = $operations;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Unable to prepare statement';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'User not logged in';
}

echo json_encode($response);
?>
