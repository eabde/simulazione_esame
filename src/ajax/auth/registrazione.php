<?php
    header('Content-Type: application/json');
    require_once("../../database/database.php");

    $response = array();

    try {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $numTelefono = $_POST['numTelefono'];
        $cartaCredito = $_POST['cc'];
        $password = $_POST['password'];
        $via = $_POST['via'];
        $citta = $_POST['citta'];
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

            $insert_utente_query = "INSERT INTO utenti (nome, cognome, email, numTelefono, cartaCredito, password, via, città, provincia, regione) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_utente_stmt = $conn->prepare($insert_utente_query);
            $insert_utente_stmt->bind_param("ssssssssss", $nome, $cognome, $email, $numTelefono, $cartaCredito, $hashed_password, $via, $citta, $provincia ,$regione);

            if ($insert_utente_stmt->execute()) {
                $response['status'] = "success";
                $response['message'] = "Registrazione avvenuta con successo!";
            } else {
                $response['status'] = "error";
                $response['message'] = "Errore durante la registrazione dell'utente. Riprova.";
            }

            $insert_utente_stmt->close();
     
        }

        $check_stmt->close();
    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    $conn->close();
?>
