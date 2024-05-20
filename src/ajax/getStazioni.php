<?php
header('Content-Type: application/json');
require_once("../database/database.php");

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
    $sql = "SELECT s.*, i.via , i.città, i.provincia, i.regione 
            FROM stazioni s 
            JOIN indirizzi i ON s.indirizzo = i.ID";
    $result = $conn->query($sql);

    $stazioni = array();
    while ($row = $result->fetch_assoc()) {
        $address =  $row['via'] . ', ' . $row['città'] . ', ' . $row['provincia'] . ', ' . $row['regione'];
        $coordinates = getCoordinates($address);
        
        if ($coordinates) {
            $row['latitudine'] = $coordinates['lat'];
            $row['longitudine'] = $coordinates['lng'];
            $stazioni[] = $row;
        } else {
            throw new Exception("Unable to get coordinates for address: $address");
        }
    }

    echo json_encode($stazioni);

} catch (Exception $e) {
    $response['status'] = "error";
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}

$conn->close();
?>
