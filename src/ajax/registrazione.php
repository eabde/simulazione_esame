<?php
    header('Content-Type: application/json');
    require_once("../database/database.php");

    $response = array();

    try {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $numTelefono = $_POST['numTelefono'];
        $cartaCredito = $_POST['cc'];
        $password = $_POST['password'];
        $via = $_POST['via'];
        $comune = $_POST['comune'];
        $provincia = $_POST['provincia'];
        $regione = $_POST['regione'];

        // Controllo se l'utente esiste già
        $check_query = "SELECT * FROM utenti WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $response['status'] = "error";
            $response['message'] = "Utente gia registrato";
        } else {
            $hashed_password = md5($password);

            $insert_indirizzo_query = "INSERT INTO indirizzi (via, città, provincia, regione) VALUES (?, ?, ?, ?)";
            $insert_indirizzo_stmt = $conn->prepare($insert_indirizzo_query);
            $insert_indirizzo_stmt->bind_param("ssss", $via, $comune,$provincia, $regione);

            if ($insert_indirizzo_stmt->execute()) {
                $indirizzo_id = $conn->insert_id;

                $insert_utente_query = "INSERT INTO utenti (nome, cognome, email, numTelefono, cartaCredito, password, indirizzo) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $insert_utente_stmt = $conn->prepare($insert_utente_query);
                $insert_utente_stmt->bind_param("ssssssi", $nome, $cognome, $email, $numTelefono, $cartaCredito, $hashed_password, $indirizzo_id);

                if ($insert_utente_stmt->execute()) {
                    $conn->commit();
                    $response['status'] = "success";
                    $response['message'] = "Registrazione avvenuta con successo!";
                } else {
                    $conn->rollback();
                    $response['status'] = "error";
                    $response['message'] = "Errore durante la registrazione dell'utente. Riprova.";
                }

                $insert_utente_stmt->close();
            } else {
                $conn->rollback();
                $response['status'] = "error";
                $response['message'] = "Errore durante la registrazione dell'indirizzo. Riprova.";
            }
            $insert_indirizzo_stmt->close();
        }

        $check_stmt->close();
    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    $conn->close();
?>
