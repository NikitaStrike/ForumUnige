<!DOCTYPE HTML>
<html lang="it">
<head>
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/util.js"></script>
</head>

<body>
    <div class='content'>
    <?php
    require_once('util/adminFunction.php');
    require_once('util/testInput.php');
    require_once('util/utilFunction.php');
    require_once('util/connection.php');
    if (isset($_POST) && !empty($_POST)) {
        testMatricola($_POST['matricola']) ? $matricola = $_POST['matricola'] : $matricolaerrorMessage = 'La matricola non rispetta la sintassi';
        testEmail($_POST['email']) ? $email = $_POST['email'] : $mailerrorMessage = 'La mail non rispetta la sintassi';
        checkDoublePwd($_POST['password'], $_POST['passwordrepeat']) ? $pwd = hash('sha256', $_POST['password']) : $pwderrorMessage = 'Le password non coincidono';
        empty($_POST['corsoDiLaurea']) ? $cdlerrorMessage='Indicare il corso di laurea' : $corso=$_POST['corsoDiLaurea'];
        empty($_POST['anno']) ? $annoerrorMessage='Indicare l\'anno di iscrizione' : $anno = $_POST['anno'];
        if($corso=='Design')
            $corso = 'Design del prodotto e dell\'evento';
        if (empty($matricolaerrorMessage) && empty($mailerrorMessage) && empty($pwderrorMessage) && empty($cdlerrorMessage) && empty($annoerrorMessage)) {
            $conn = connection();
            if (insertStudent($conn,$matricola, $email, $pwd, $corso, $anno)) {
                    $conn->close();
                    header('Location: redirect.php?type=signup');
                    exit();
            } else
                $errorMessage = "Non è stato possibile completare la registrazione";
        }
    }
    if(isset($_POST))
        if(!empty($errorMessage))
            echo "<div class='errorAfterPost'><p>".$errorMessage."</p></div>";
    ?>
    <div class="boxLogSign">
        <h1>Sign Up</h1>
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <div id="inputform">
                <div class="singleInput">
                    <input class="input" type="text" name="matricola" placeholder="Matricola" value="<?php if(isset($_POST)) if(!empty($matricola)) echo $matricola; ?>" pattern="[sS]\d{7}" required>
                    <?php if(isset($_POST)) if(!empty($matricolaerrorMessage)){echo "<script>changeMatricolaBorder()</script>"; echo "<div class='error'><p>".$matricolaerrorMessage."</p></div>";}?>
                </div>
                <div class="singleInput">
                    <input class="input" type="email" name="email" placeholder="Email" pattern="[a-zA-Z0-9\-!#$%&'*+/=?^_`{|}~\.]+@[a-zA-Z0-9\.\-?]+\.[a-zA-Z0-9\.]+" value="<?php if(isset($_POST)) if(!empty($email)) echo $email; ?>" required>
                    <?php if(isset($_POST)) if(!empty($mailerrorMessage)){ echo "<script>changeEmailBorder()</script>"; echo "<div class='error'><p>".$mailerrorMessage."</p></div>";}?>
                </div>
                <input id="pwd" class="input" type="password" name="password" placeholder="Password" value="<?php if(isset($_POST)) if(!empty($pwd)) echo $pwd; ?>" required>
                <div class="singleInput">
                    <input id="pwdR" class="input" type="password" name="passwordrepeat" placeholder="Ripeti password" required>
                    <?php if(isset($_POST)) if(!empty($pwderrorMessage)) {echo "<script>changePwdBorder()</script>"; echo "<div class='error'><p>".$pwderrorMessage."</p></div>";}?>
                </div>
                <div class="singleInput">
                <select required name="corsoDiLaurea" id="corsodiLaurea">
                    <option value="">Corso di Laurea</option>
                    <option value="Informatica">Informatica</option>
                    <option value="Design">Design del prodotto e dell'evento</option>
                </select>
                    <?php if(isset($_POST)) if(!empty($cdlerrorMessage)) { echo "<div class='error'><p>".$cdlerrorMessage."</p></div>";}?>
                </div>
                <div class="singleInput">
                <select name="anno" id="anno" required>
                    <option value="">Anno</option>
                    <option value="primo">Primo (LT - LM)</option>
                    <option value="secondo">Secondo (LT - LM)</option>
                    <option value="terzo">Terzo (LT)</option>
                </select>
                <?php if(isset($_POST)) if(!empty($annoerrorMessage)) {echo "<div class='error'><p>".$annoerrorMessage."</p></div>";}?>
                </div>
                <input type="submit" class="button" value="signup">
            </div>
        </form>
        <hr>
        <div class='button'><a href="login.php">Login</a></div>
    </div>
</div>
<footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>