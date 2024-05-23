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
                    $response['message'] = "Operazione conclusa con successo";
                    echo json_encode($response);
                    
                    ignore_user_abort(true);
                    set_time_limit(0);
                    
                    //loop infinito per simulare il noleggio finchè non riconsegna la bici
                    while (true) {
                        // controllo se esiste un'operazione di riconsegna per questa bicicletta
                        $checkRiconsegnaQuery = "SELECT tipo FROM operazioni WHERE idBicicletta = ? ORDER BY ID DESC LIMIT 1";
                        $stmt = $conn->prepare($checkRiconsegnaQuery);
                        $stmt->bind_param("i", $idBicicletta);
                        $stmt->execute();
                        $riconsegnaResult = $stmt->get_result();
                        //una bici puo essere noleggiata più volte anche dopo la riconsegna
                        if ($riconsegnaResult->num_rows > 0) {
                            $ultimoTipoOperazione = $riconsegnaResult->fetch_assoc()['tipo'];
                            if ($ultimoTipoOperazione === 'riconsegna') {
                                break; // se l'ultima operazione è una riconsegna, esco dal ciclo
                            }
                        }
                        
                        sleep(30); //ogni 30 secondi cambia posizione
                        
                        //chiamata cURL per eseguire simulaSpostamento.php e aggiornare i dati nel DB
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "http://localhost/simulazione_esame/src/ajax/simulaSpostamento.php?id=" . $idBicicletta);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_exec($ch);
                        curl_close($ch);
                    }

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

$conn->close();
?>
