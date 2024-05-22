<?php
header('Content-Type: application/json');
require_once("../../database/database.php");
session_start();

$response = array();

try {
    $idUtente = $_SESSION["IdUtente"];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $numTelefono = $_POST['numTelefono'];
    $cartaCredito = $_POST['cartaCredito'];
    $smartCard = $_POST['smartCard'];
    $via = $_POST['via'];
    $citta = $_POST['citta'];
    $provincia = $_POST['provincia'];
    $regione = $_POST['regione'];

    $sql = "UPDATE utenti SET nome = ?, cognome = ?, email = ?, numTelefono = ?, cartaCredito = ?, smartCard = ?, via = ?, città = ?, provincia = ?, regione = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $nome, $cognome, $email, $numTelefono, $cartaCredito, $smartCard, $via, $citta, $provincia ,$regione, $idUtente);

    if ($stmt->execute()) {
        $response['status'] = "success";
        $response['message'] = "Profilo aggiornato con successo!";
    } else {
        $response['status'] = "error";
        $response['message'] = "Errore durante l'aggiornamento del profilo. Riprova.";
    }

    $stmt->close();
} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>
