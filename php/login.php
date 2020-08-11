<?php
session_start();
require_once('util/testInput.php');
require_once('util/retrieve.php');
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script  src="../js/util.js"></script>
</head>
<body>
    <div class='content'>
    <?php
    if (!empty($_SESSION['email'])) {
        header('Location:show_profile.php');
        exit();
    }
    if (isset($_POST) && !empty($_POST)) {
        $conn = connection();
        if (!testEmail($_POST['email']))
            $_SESSION["errorMessage"] = "User o password errati";
        else {
            $email = $_POST['email'];                
            $pwdFromDb = retrieveUserPwdFromMail($conn,$email);
            if ($pwdFromDb === 0)
                $_SESSION["errorMessage"] = "User o password errati";
            else {
                $pwd = $_POST['pass'];
                if ($pwdFromDb === hash('sha256', $pwd)) {
                    $_SESSION['email'] = $email;
                    if(!$matricola = retrieveSerialNumberFromMail($conn,$email))
                        $_SESSION['errorMessage'] = "User o password errati";
                    else
                        $_SESSION['matricola'] = $matricola;
                    unset($_SESSION["errorMessage"]);
                    $conn->close();
                    header("Location:show_profile.php");
                    exit();
                } else
                    $_SESSION["errorMessage"] = "User o password errati";
            }
        }
    } 
    ?>
    <?php
    if (isset($_POST))
        if (!empty($_SESSION['errorMessage']))
        {
            echo ("<div class='errorAfterPost'><p>" . $_SESSION['errorMessage'] . "</p></div>");
            unset($_SESSION['errorMessage']);
        }
    ?>
    <div class="boxLogSign">
        <h1>Sign In</h1>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <input class='input' type="email" name="email" placeholder="Email" pattern="[a-zA-Z0-9\-!#$%&'*+/=?^_`{|}~\.]+@[a-zA-Z0-9\.\-?]+\.[a-zA-Z0-9\.]+" required>
            <input class='input' type="password" name="pass" placeholder="Password" required>
            <a href="retrievepwd.php">Forgot your password?</a>
            <input type="submit" class="button" value="login">
        </form>
        <hr>
        <div class="button"><a href="signup.php">Registrati</a></div>
    </div>
</div>
<footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>