<?php

require_once("../database/database.php");

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$response = array();

try {
    $codiceTag = generateRandomString(6);
    $gps = generateRandomString(6);

    $sql = "INSERT INTO biciclette (codiceTag, gps) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $codiceTag, $gps);
    
    if($stmt->execute()) {
        $response['status'] = "ok";
        $response['message'] = "Bicicletta aggiunta con successo";
    } else {
        $response['status'] = "error";
        $response['message'] = "Errore durante l'inserimento della bicicletta con codiceTag: $codiceTag. " . $stmt->error;
    }
    $stmt->close();

} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
