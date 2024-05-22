<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Utente non registrato');</script>";
        header("Location: ../ajax/logout.php");
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
        <title>User</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <script>
        $(document).ready(function(){
            $('#viewMapBtn').click(function(){
                window.location.href = '../mappa.html';
            });
            $('#tratteBtn').click(function(){
                window.location.href = '';
            });
            $('#riepiloghiBtn').click(function(){
                window.location.href = '';
            });
            $('#profiloBtn').click(function(){
                window.location.href = 'userProfile.php';
            });
        });
    </script>

    </head>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <b>Benvenuto</b>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <body>
        <div class="btn-container">
            <button type="button" id="viewMapBtn" class="btn btn-warning">Visualizza mappa</button>
            <button type="button" id="tratteBtn" class="btn btn-warning">Visualizza tratte percorse</button>
            <button type="button" id="riepiloghiBtn" class="btn btn-warning">Visualizza riepiloghi</button>
            <button type="button" id="profiloBtn" class="btn btn-warning">Profilo</button>
        </div>
    </body>

</html>