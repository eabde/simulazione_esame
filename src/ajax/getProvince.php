<?php
    require_once("../database/gi_db_comuni.php");

    $sql = "SELECT sigla_provincia, denominazione_provincia FROM gi_province";
    $result = $conn->query($sql);

    $province = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $province[] = $row;
        }
    }

    echo json_encode($province);

    $conn->close();
?>
