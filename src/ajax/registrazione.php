<?php
    header('Content-Type: application/json');
    require_once("../database/database.php");

    $response = array();

    try{
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Controllo se l'utente esiste già
        $check_query = "SELECT * FROM utenti WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $response['status'] = "error";
            $response['message'] = "Username già in uso.";
        } else {
            // Hash della password con MD5
            $hashed_password = md5($password);

            // Inserimento dell'utente nel database
            $insert_query = "INSERT INTO utenti (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ss", $username, $hashed_password);

            if ($insert_stmt->execute()) {
                $response['status'] = "success";
                $response['message'] = "Registrazione avvenuta con successo!";
            } else {
                $response['status'] = "error";
                $response['message'] = "Errore durante la registrazione. Riprova.";
            }
        }

        $check_stmt->close();
        $insert_stmt->close();

    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
        echo json_encode($response);
    }

    echo json_encode($response);
    $conn->close();
?>
