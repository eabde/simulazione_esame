<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');
    require_once("../database/database.php");

    session_start();

    $response = array();

    try {
        $email = $_GET['email'];
        $password = $_GET['password'];

        $hashed_password = md5($password);

        //controllo admin
        $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = "ok";
            $response['tipoUtente'] = "admin";
            $response['message'] = "Login avvenuto con successo";
        } else {
            //controllo user
            $sql = "SELECT * FROM utenti WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashed_password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['status'] = "ok";
                $response['tipoUtente'] = "user";
                $response['message'] = "Login avvenuto con successo";
            } else {
                $response['status'] = "error";
                $response['message'] = "Credenziali non valide. Riprova.";
            }
        }

        $stmt->close();

    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    $conn->close();
?>
