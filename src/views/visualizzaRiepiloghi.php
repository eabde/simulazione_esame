<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Utente non registrato');</script>";
        header("Location: ../logout.php");
        exit();
    }
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/login.css">
    <title>View Riepiloghi</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                type: 'GET',
                url: '../ajax/utenti/getRiepiloghi.php',
                dataType: 'json',
                success: function(response) {
                    var table = $('#tabellaRiepiloghi');
                    $.each(response.data, function(index, operazione) {
                        table.append(
                            '<tr>' +
                                '<td>' + operazione.ID + '</td>' +
                                '<td>' + operazione.tipo + '</td>' +
                                '<td>' + operazione.data + '</td>' +
                                '<td>' + operazione.ora + '</td>' +
                                '<td>' + operazione.distanzaPercorsa + '</td>' +
                                '<td>' + operazione.idStazione + '</td>' +
                                '<td>' + operazione.idBicicletta + '</td>' +
                            '</tr>'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    alert("Errore nella richiesta AJAX.");
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
</head>
<body>
    <!-- <button class="aggiungi btn btn-primary">Aggiungi operazione</button><br><br> -->
    <h1>Visualizza Riepiloghi </h1>
    <table id="tabellaRiepiloghi" class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Data</th>
            <th>Ora</th>
            <th>Distanza Percorsa</th>
            <th>idStazione</th>
            <th>idBicicletta</th>
        </tr>
    </table>
</body>
</html>
