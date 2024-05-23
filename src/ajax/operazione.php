<?php

require_once("../database/database.php");

header('Content-Type: application/json');
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // verifica chiavi $_GET
    if (isset($_GET['tipo'], $_GET['idStazione'], $_GET['idUtente'], $_GET['idBicicletta'])) {
        $tipo = $_GET['tipo'];
        $idStazione = $_GET['idStazione'];
        $idUtente = $_GET['idUtente'];
        $idBicicletta = $_GET['idBicicletta'];
        $distanzaPercorsa = 0;  // inizialmente 0 km

        // posizione della stazione
        $stazioneQuery = "SELECT latitudine, longitudine FROM stazioni WHERE ID = ?";
        $stmt = $conn->prepare($stazioneQuery);
        $stmt->bind_param("i", $idStazione);
        $stmt->execute();
        $stazioneResult = $stmt->get_result();
        
        if ($stazioneResult->num_rows > 0) {
            $stazione = $stazioneResult->fetch_assoc();

            $latitudine = $stazione['latitudine'];
            $longitudine = $stazione['longitudine'];

            // aggiorno la posizione della bicicletta con quelli della stazione
            $updateBiciclettaQuery = "UPDATE biciclette SET latitudine = ?, longitudine = ? WHERE ID = ?";
            $stmt = $conn->prepare($updateBiciclettaQuery);
            $stmt->bind_param("ddi", $latitudine, $longitudine, $idBicicletta);
            $stmt->execute();

            try {
                $sql = "INSERT INTO operazioni (tipo, distanzaPercorsa, idUtente, idBicicletta, idStazione) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siiii", $tipo, $distanzaPercorsa, $idUtente, $idBicicletta, $idStazione);

                if ($stmt->execute()) {
                    $response['status'] = "success";
                    $response['message'] = "Bicicletta noleggiata con successo";
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Errore durante il noleggio della bicicletta. Riprova";
                }

                $stmt->close();
            } catch (Exception $e) {
                $response['status'] = "error";
                $response['message'] = "Errore durante l'inserimento dell'operazione: " . $e->getMessage();
            }
        } else {
            $response['status'] = "error";
            $response['message'] = "Stazione non trovata";
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "Dati mancanti";
    }
} else {
    $response['status'] = "error";
    $response['message'] = "Richiesta non valida";
}

echo json_encode($response);
$conn->close();
?>