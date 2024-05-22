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
    <title>View Stazioni</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                type: 'GET',
                url: '../ajax/stazioni/getStazioni.php',
                dataType: 'json',
                success: function(response) {
                    var table = $('#tabellaStazioni');
                    $.each(response, function(index, stazione) {
                        table.append(
                            '<tr>' +
                                '<td>' + stazione.ID + '</td>' +
                                '<td>' + stazione.numSlot + '</td>' +
                                '<td>' + stazione.numBiciclette + '</td>' +
                                '<td>' + stazione.via + '</td>' +
                                '<td>' +
                                    '<button class="modifica btn btn-warning" data-id="' + stazione.ID + '">Modifica</button>' +
                                '</td>' +
                                '<td>' +
                                    '<button class="elimina btn btn-danger" data-id="' + stazione.ID + '">Elimina</button>' +
                                '</td>' +
                                
                            '</tr>'
                        );
                    });

                    $('.modifica').click(function() {
                        var id = $(this).data('id');
                        var row = $(this).closest('tr');
                        var stazione = {
                            id: id,
                            numSlot: row.find('td:eq(1)').text(),
                            numBiciclette: row.find('td:eq(2)').text(),
                            via: row.find('td:eq(3)').text(),
                            citta: 'Milano',
                            provincia: 'Milano',
                            regione: 'Lombardia'
                        };

                        localStorage.setItem('stazione', JSON.stringify(stazione));
                        window.location.href = 'modificaStazione.php';
                    });


                    $('.elimina').click(function(){
                        var id = $(this).data('id');
                        if (confirm('Sei sicuro di voler eliminare la stazione con ID: ' + id + '?')) {
                            $.ajax({
                                type: 'GET',
                                url: '../ajax/stazioni/eliminaStazione.php',
                                data: { id: id },
                                success: function(response) {
                                    alert('Stazione eliminata con successo.');
                                    location.reload();
                                },
                                error: function(xhr, status, error) {
                                    alert('Errore nella richiesta AJAX.');
                                    console.log(xhr.responseText);
                                }
                            });
                        }
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
    <table id="tabellaStazioni" class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Numero Slot</th>
            <th>Numero Biciclette</th>
            <th>Via</th>
        </tr>
    </table>
</body>
</html>
