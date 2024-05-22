<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
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
    <title>View Biciclette</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                type: 'GET',
                url: '../ajax/biciclette/getBiciclette.php',
                dataType: 'json',
                success: function(response) {
                    var table = $('#tabellaBiciclette');
                    $.each(response.data, function(index, bicicletta) {
                        table.append(
                            '<tr>' +
                                '<td>' + bicicletta.ID + '</td>' +
                                '<td>' + bicicletta.codiceTag + '</td>' +
                                '<td>' + bicicletta.latitudine + '</td>' +
                                '<td>' + bicicletta.longitudine + '</td>' +
                                '<td>' + bicicletta.distanzaPercorsa + '</td>' +
                                '<td>' + bicicletta.gps + '</td>' +
                                '<td>' +
                                    '<button class="modifica btn btn-warning" data-id="' + bicicletta.ID + '">Modifica</button>' +
                                '</td>' +
                                '<td>' +
                                    '<button class="elimina btn btn-danger" data-id="' + bicicletta.ID + '">Elimina</button>' +
                                '</td>' +
                                
                            '</tr>'
                        );
                    });

                    $('.modifica').click(function() {
                        var id = $(this).data('id');
                        var row = $(this).closest('tr');
                        var bicicletta = {
                            id: id,
                            codiceTag: row.find('td:eq(1)').text(),
                            latitudine: row.find('td:eq(2)').text(),
                            longitudine: row.find('td:eq(3)').text(),
                            distanzaPercorsa: row.find('td:eq(4)').text(),
                            gps: row.find('td:eq(5)').text()
                        };

                        localStorage.setItem('bicicletta', JSON.stringify(bicicletta));
                        window.location.href = 'modificaBicicletta.php';
                    });


                    $('.elimina').click(function(){
                        var id = $(this).data('id');
                        if (confirm('Sei sicuro di voler eliminare la bicicletta con ID: ' + id + '?')) {
                            $.ajax({
                                type: 'GET',
                                url: '../ajax/biciclette/eliminaBicicletta.php',
                                data: { id: id },
                                success: function(response) {
                                    alert('Bicicletta eliminata con successo.');
                                    location.reload();
                                },
                                error: function(xhr, status, error) {
                                    alert('Errore nella richiesta AJAX.');
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });

                    $('.aggiungi').click(function(){
                        $.ajax({
                            type: 'GET',
                            url: '../ajax/biciclette/createBicicletta.php',
                            dataType: 'json',
                            success: function(response) {
                                alert('Bicicletta aggiunta con successo.');
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                alert('Errore nella richiesta AJAX.');
                                console.log(xhr.responseText);
                            }
                        });  
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
    <button class="aggiungi btn btn-primary">Aggiungi Bicicletta</button><br><br>
    <table id="tabellaBiciclette" class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>codiceTag</th>
            <th>latitudine</th>
            <th>longitudine</th>
            <th>distanza percorsa</th>
            <th>GPS</th>
        </tr>
    </table>
</body>
</html>
