<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gi_db_comuni";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM gi_regioni";
    $result = $conn->query($sql);

    $regioni = array();
    while($row = $result->fetch_assoc()) {
        $regioni[] = $row;
    }

    echo json_encode($regioni);  

    $conn->close();
?>
