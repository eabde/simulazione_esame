<?php

header('Content-Type: application/json');
require_once("../database/database.php");


$response = array();

try {
    $sql = "SELECT * FROM biciclette";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $biciclette = array();
        while ($row = $result->fetch_assoc()) {
            $biciclette[] = $row;
        }
        $response['status'] = "success";
        $response['data'] = $biciclette;
    } else {
        $response['status'] = "error";
        $response['message'] = "Nessuna bicicletta trovata.";
    }
} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
