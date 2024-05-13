<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gi_db_comuni";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM gi_province";
    $result = $conn->query($sql);

    $province = array();
    while($row = $result->fetch_assoc()) {
        $province[] = $row;
    }

    echo json_encode($province);  

    $conn->close();
?>
