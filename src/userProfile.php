<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Utente non registrato');</script>";
    header("Location: logout.php");
    exit; 
}
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <title>User Profile</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            getData();
            $("#userProfileForm").submit(function(e) {
                e.preventDefault();
                updateData(this);
            });
        });

        function getData() {
            $.ajax({
                type: 'GET',
                url: 'ajax/getUserProfile.php',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "ok") {
                        $('#nome').val(response.data.nome);
                        $('#cognome').val(response.data.cognome);
                        $('#email').val(response.data.email);
                        $('#numTelefono').val(response.data.numTelefono);
                        $('#cartaCredito').val(response.data.cartaCredito);
                        $('#smartCard').val(response.data.smartCard);
                        $('#via').val(response.data.via);
                        $('#citta').val(response.data.città);
                        $('#provincia').val(response.data.provincia);
                        $('#regione').val(response.data.regione);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Errore nella richiesta AJAX.");
                    console.log(xhr.responseText);
                }
            });
        }

        function updateData(form) {
            var formData = $(form).serialize();

            $.ajax({
                type: 'POST',
                url: 'ajax/updateUserProfile.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    window.location.href = "userDashboard.php";
                },
                error: function(xhr, status, error) {
                    alert("Errore nella richiesta AJAX.");
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
</head>

<body>
    <h1>Profilo Utente</h1>
    <form id="userProfileForm">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>

        <label for="cognome" class="form-label">Cognome</label>
        <input type="text" class="form-control" id="cognome" name="cognome" required>

        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>

        <label for="numTelefono" class="form-label">Numero di Telefono</label>
        <input type="text" class="form-control" id="numTelefono" name="numTelefono" required>

        <label for="cartaCredito" class="form-label">Carta di Credito</label>
        <input type="text" class="form-control" id="cartaCredito" name="cartaCredito" required>

        <label for="smartCard" class="form-label">Smart Card</label>
        <input type="text" class="form-control" id="smartCard" name="smartCard" required>

        <label for="via" class="form-label">Via</label>
        <input type="text" class="form-control" id="via" name="via" required>

        <label for="citta" class="form-label">Città</label>
        <input type="text" class="form-control" id="citta" name="citta" required>

        <label for="provincia" class="form-label">Provincia</label>
        <input type="text" class="form-control" id="provincia" name="provincia" required>

        <label for="regione" class="form-label">Regione</label>
        <input type="text" class="form-control" id="regione" name="regione" required>

        <button type="submit" class="btn btn-primary">Aggiorna</button>
    </form>
</body>

</html>
