<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');
    require_once("../database/database.php");

    session_start();

    $response = array();

    try {
        $username = $_GET['username'];
        $password = $_GET['password'];

        $hashed_password = md5($password);

        $sql = "SELECT * FROM utenti WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["username"] = $username;
            $response['status'] = "ok";
            $response['message'] = "Login avvenuto con successo!";
        } else {
            $response['status'] = "error";
            $response['message'] = "Credenziali non valide. Riprova.";
        }

        $stmt->close();

    } catch (Exception $e) {
        $response['status'] = "error";
        $response['message'] = $e->getMessage();
        echo json_encode($response);
    }

    echo json_encode($response);
    $conn->close();
?>
