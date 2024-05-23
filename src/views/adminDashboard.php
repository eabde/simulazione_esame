<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        echo "<script>alert('Utente non registrato');</script>";
        header("Location: ../logout.php");
        exit; 
    }
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Admin</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    
    <script>
        $(document).ready(function(){
            $('#addStationBtn').click(function(){
                window.location.href = 'aggiungiStazione.php';
            });
            $('#viewBiciBtn').click(function(){
                window.location.href = 'visualizzaBiciclette.php';
            });
            $('#viewStazioniBtn').click(function(){
                window.location.href = 'visualizzaStazioni.php';
            });
            $('#viewMapBtn').click(function(){
                window.location.href = '../mappa.php';
            });
        });
    </script>

   
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <b>Admin</b>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<body>
    <div class="btn-container">
        <button type="button" class="btn btn-warning" id="addStationBtn">Aggiungi stazione</button>
        <button type="button" class="btn btn-warning" id="viewBiciBtn">Visualizza informazioni biciclette</button>
        <button type="button" class="btn btn-warning" id="viewStazioniBtn">Visualizza informazioni stazioni</button>
        <button type="button" class="btn btn-warning" id="viewMapBtn">Visualizza mappa</button>
    </div>
</body>

</html>