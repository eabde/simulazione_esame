<?php
    require_once("../database/gi_db_comuni.php");

    $sql = "SELECT codice_regione, denominazione_regione FROM gi_regioni";
    $result = $conn->query($sql);

    $regioni = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $regioni[] = $row;
        }
    }

    echo json_encode($regioni);

    $conn->close();
?>
