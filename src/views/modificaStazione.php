<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        echo "<script>alert('Utente non registrato');</script>";
        header("Location: ../ajax/logout.php");
        exit();
    }
?>
<html>
<head>
    <title>Modifica Stazione</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            //carica i dati della stazione da localStorage
            let stazione = JSON.parse(localStorage.getItem('stazione'));
            if (stazione) {
                $('#numSlot').val(stazione.numSlot);
                $('#numBiciclette').val(stazione.numBiciclette);
                $('#via').val(stazione.via);
                $('#citta').val(stazione.citta);
                $('#provincia').val(stazione.provincia);
                $('#regione').val(stazione.regione);
                $("#stazioneId").val(stazione.id);
            }

            $("#modificaStazioneForm").submit(function(e) {
                e.preventDefault();
                modificaStazione();
            });
        });

        function modificaStazione() {
            let id = $('#stazioneId').val();
            let numSlot = $('#numSlot').val();
            let numBiciclette = $('#numBiciclette').val();
            let via = $('#via').val();
            let citta = $('#citta').val();
            let provincia = $('#provincia').val();
            let regione = $('#regione').val();
            $.ajax({
                url: '../ajax/stazioni/updateStazione.php',
                type: 'POST',
                data: {
                    id: id,
                    numSlot: numSlot,
                    numBiciclette: numBiciclette,
                    via: via,
                    citta: citta,
                    provincia: provincia,
                    regione: regione,
                },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    window.location.href = 'visualizzaStazioni.php';
                },
                error: function() {
                    alert('Si e verificato un errore durante la modifica della stazione');
                }
            });
        }
    </script>
</head>
<body>
    <h1>Modifica Stazione</h1>
    <form id="modificaStazioneForm">
        <input type="hidden" id="stazioneId" name="stazioneId">
        <input type="number" id="numSlot" name="numSlot" placeholder="Numero slot"><br>
        <input type="number" id="numBiciclette" name="numBiciclette" placeholder="Numero biciclette"><br>
        <input type="text" id="via" name="via" placeholder="Via"><br>

        <label for="citta">Citta</label><br>
        <input type="text" id="citta" name="citta" placeholder="Milano" value="Milano" disabled><br>

        <label for="provincia">Provincia</label><br>
        <input type="text" id="provincia" name="provincia" placeholder="Milano" value="Milano" disabled><br>

        <label for="regione">Regione</label><br>
        <input type="text" id="regione" name="regione" placeholder="Lombardia" value="Lombardia" disabled><br>

        <input type="submit" id="submit" value="Modifica Stazione">
    </form>
</body>
</html>
