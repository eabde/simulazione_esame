<?php
header('Content-Type: application/json');
require_once("../../database/database.php");

$response = array();

function getCoordinates($address) {
    $apiKey = 'AIzaSyCfPK_iMhgxJMbmraGqBe_L6faknvXZXak';
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$apiKey";
    
    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if ($json['status'] === 'OK') {
        $lat = $json['results'][0]['geometry']['location']['lat'];
        $lng = $json['results'][0]['geometry']['location']['lng'];
        return array('lat' => $lat, 'lng' => $lng);
    } else {
        return null;
    }
}

try {
    $sql = "SELECT * FROM stazioni";
    $result = $conn->query($sql);

    $stazioni = array();
    while ($row = $result->fetch_assoc()) {
        if ($row['latitudine'] == 0 && $row['longitudine'] == 0) {
            $address = $row['via'] . ', ' . $row['città'] . ', ' . $row['provincia'] . ', ' . $row['regione'];
            $coordinates = getCoordinates($address);

            if ($coordinates) {
                $row['latitudine'] = $coordinates['lat'];
                $row['longitudine'] = $coordinates['lng'];

                // Aggiorna la tabella stazioni con le nuove coordinate
                $updateStmt = $conn->prepare("UPDATE stazioni SET latitudine = ?, longitudine = ? WHERE ID = ?");
                $updateStmt->bind_param("ddi", $row['latitudine'], $row['longitudine'], $row['ID']);
                $updateStmt->execute();
            } else {
                throw new Exception("Unable to get coordinates for address: $address");
            }
        }
        $stazioni[] = $row;
    }

    echo json_encode($stazioni);

} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}

$conn->close();
?>
