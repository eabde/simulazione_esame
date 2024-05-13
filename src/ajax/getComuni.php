<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gi_db_comuni";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM gi_comuni"; 
    $result = $conn->query($sql);

    $comuni = array();
    while($row = $result->fetch_assoc()) {
        $comuni[] = $row;
    }

    echo json_encode($comuni); 

    $conn->close();
?>
