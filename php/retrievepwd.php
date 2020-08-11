<?php
session_start();
require_once('util/testInput.php');
require_once('util/retrieve.php');
require_once('util/adminFunction.php');
require_once('util/connection.php');
if (isset($_POST) && !empty($_POST)) {
    if (!testEmail($_POST['email']))
        $_SESSION['errorMessage'] = "Formato email errato";
    else {
        if (!checkDoublePwd($_POST['password'], $_POST['passwordrepeat']))
            $_SESSION['errorMessage'] = "Le password non coincidono";
        else {
            $pwd = hash('sha256', $_POST['password']);
            $conn = connection();
            if (!updatePassword($conn,$_POST['email'], $pwd))
                $_SESSION['errorMessage'] = "Non è stato possibile aggiornare la password.La password potrebbe essere già stata usata, o la matricola è errata";
            else {
                $conn->close();
                unset($_SESSION['errorMessage']);
                header("Location:redirect.php?type=pwd");
                exit();
            }
        }
    }
}
?>
<html>

<head>
    <title>Reimposta password</title>
    <link rel="stylesheet" type="text/css" href="..\css\style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    if (!empty($_SESSION['errorMessage'])) {
        echo ("<div class='error'>" . $_SESSION['errorMessage'] . "</div>");
        unset($_SESSION['errorMessage']);
    }
    ?>
    <div class="boxLogSign">
        <h1>Reimposta password</h1>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <input class='input' type="text" name="email" placeholder="Email" pattern="[a-zA-Z0-9\-!#$%&'*+/=?^_`{|}~\.]+@[a-zA-Z0-9\.\-?]+\.[a-zA-Z0-9\.]+" required>
            <input class ='input' type="password" name="password" placeholder="Password">
            <input class ='input' type="password" name="passwordrepeat" placeholder="Ripeti Password">
            <input type="submit" class="button" name="save" value="save">
        </form>
    </div>
</body>

</html>