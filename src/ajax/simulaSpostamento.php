<?php
require_once("../database/database.php");
header('Content-Type: application/json');

function simulateGPS($lastLat, $lastLon) {
    //spostamento di circa 200 metri
    $delta = 0.0018;
    $newLat = $lastLat + (rand(-10, 10) / 10 * $delta);
    $newLon = $lastLon + (rand(-10, 10) / 10 * $delta);
    return array($newLat, $newLon);
}

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Ottieni l'ultima posizione registrata della bicicletta specifica
        $lastPositionQuery = "SELECT latitudine, longitudine FROM biciclette WHERE ID = ? LIMIT 1";
        $stmt = $conn->prepare($lastPositionQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $lastPositionResult = $stmt->get_result();

        if ($lastPositionResult->num_rows > 0) {
            $lastPosition = $lastPositionResult->fetch_assoc();
            $lastLat = $lastPosition['latitudine'];
            $lastLon = $lastPosition['longitudine'];

            //nuova posizione
            list($newLat, $newLon) = simulateGPS($lastLat, $lastLon);
            $distanzaPercorsa = haversineGreatCircleDistance($lastLat, $lastLon, $newLat, $newLon);

            //aggiorna la nuova posizione della bicicletta nel database
            $updateQuery = "UPDATE biciclette SET latitudine = ?, longitudine = ?, distanzaPercorsa = distanzaPercorsa + ? WHERE ID = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("dddi", $newLat, $newLon, $distanzaPercorsa, $id);

            if ($stmt->execute()) {
                $response['status'] = "success";
                $response['message'] = "Nuova posizione GPS della bicicletta aggiornata";
                $response['newLat'] = $newLat;
                $response['newLon'] = $newLon;
                $response['distanzaPercorsa'] = $distanzaPercorsa;
            } else {
                $response['status'] = "error";
                $response['message'] = "Errore durante l'aggiornamento della nuova posizione";
            }

            $stmt->close();
        } else {
            $response['status'] = "error";
            $response['message'] = "Bicicletta non trovata";
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "ID della bicicletta non fornito";
    }
} else {
    $response['status'] = "error";
    $response['message'] = "Richiesta non valida";
}

echo json_encode($response);
$conn->close();

//distanza tra coordinate
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius / 1000; //distanza in km
}
?>
