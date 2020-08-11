<!DOCTYPE html>
<html lang="it">
<head>
  <title>Redirect</title>
  <meta http-equiv="refresh" content="4; URL=login.php">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <div class="boxForLogin">
    <?php
    if (isset($_GET) && !empty($_GET)) {
      switch ($_GET['type']) {
        case "pwd":
          echo ("<p>Password aggiornata correttamente");
          break;
        case "signup":
          echo ("<p>Welcome!</p>");
          break;
        case "signin":
          echo ("<p>Esegui il login per avere accesso al profilo</p>");
          break;
      }
    }
    ?>
    <p>Tra pochi secondi avverr&agrave; un redirect automatico alla pagina di login.<br>
      Se non vuoi aspettare <a href="login.php">clicca qui</a>.</p>
  </div>
</body>

</html>