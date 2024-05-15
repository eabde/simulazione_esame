<?php
    require_once("../database/gi_db_comuni.php");

    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    
    $query = $conn->real_escape_string($query);

    $sql = "SELECT codice_istat, denominazione_ita FROM gi_comuni WHERE denominazione_ita LIKE '$query%'";
    $result = $conn->query($sql);

    $comuni = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $comuni[] = $row;
        }
    }

    echo json_encode($comuni);

    $conn->close();
?>
