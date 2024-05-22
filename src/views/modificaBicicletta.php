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
    <title>Modifica Bicicletta</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            //carica i dati della bicicletta da localStorage
            let bicicletta = JSON.parse(localStorage.getItem('bicicletta'));
            if (bicicletta) {
                console.log(bicicletta);
                $('#codiceTag').val(bicicletta.codiceTag);
                $('#gps').val(bicicletta.gps);
                $("#biciclettaId").val(bicicletta.id);
            }

            $("#modificaBiciclettaForm").submit(function(e) {
                e.preventDefault();
                modificaBicicletta();
            });
        });

        function modificaBicicletta() {
            let id = $('#biciclettaId').val();
            let codiceTag = $('#codiceTag').val();
            let gps = $('#gps').val();
            $.ajax({
                url: '../ajax/biciclette/updateBicicletta.php',
                type: 'POST',
                data: {
                    id: id,
                    codiceTag: codiceTag,
                    gps: gps,
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    window.location.href = 'visualizzaBiciclette.php';
                },
                error: function() {
                    alert('Si e verificato un errore durante la modifica della bicicletta');
                }
            });
        }
    </script>
</head>
<body>
    <h1>Modifica bicicletta</h1>
    <form id="modificaBiciclettaForm">

        <input type="hidden" id="biciclettaId" name="biciclettaId">
        <label for="codiceTag">codiceTag</label><br>
        <input type="text" id="codiceTag" name="codiceTag" placeholder="codiceTag"><br>
        <label for="gps">GPS</label><br>
        <input type="text" id="gps" name="gps" placeholder="GPS"><br>

        <input type="submit" id="submit" value="Modifica bicicletta">
    </form>
</body>
</html>
