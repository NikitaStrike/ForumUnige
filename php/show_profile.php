<?php
session_start();
require_once('util/retrieve.php');
require_once('util/testInput.php');
require_once('util/adminFunction.php');
require_once('util/utilError.php');
require_once('util/utilFunction.php');
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">
<head>
    <title>Profilo</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script  src="../js/util.js"></script>
    <script  src="../js/externalJquery.js"></script>


</head>
<body>
    <div class='content'>
        <?php
        if (!isset($_SESSION['email']))
            redirect("signin");
        ?>
        <div class='blueHeader'>
            <div class='headerLink'>
                <div class='homeBox'>
                    <div class='homeLogo'>
                        <a href="home.php"><img src="../img/logo_UniGe_WHITE.png" alt="logo"></a>
                    </div>
                </div>
                <div class="searchBox">
                    <form name="cerca" method="post" action="cerca.php">
                        <div class='searchIcons'>
                            <input type="text" id="search" name="nome" placeholder="cerca">
                            <input type="image" class="buttonSearch" src="../img/search_black.png" alt="search">
                        </div>
                    </form>
                </div>
                <?php
                $conn = connection();
                if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
                    $proName = $_SESSION['matricola'];
                    $res = retrieveUsernameFromSN($conn,$_SESSION['matricola']);
                    if(is_string($res))
                    {
                        $_SESSION['username'] = $res;
                        $proName = $res;
                    }
                    echo ("<div class='loggedBox'><div class='profileBoxExit'><div class='profileBox'><img src='../img/person.png' alt='person'><a href='show_profile.php'>" . $proName . "</a></div><div class='profileBox'><img src='../img/exit.png' alt='exit'><a href='logout.php'>Esci</a></div></div></div>");
                    echo ("<div class='mobileLoggedBox'><a href='show_profile.php'><img src='../img/person_36px.png' alt='person'></a></div>");
                }
                ?>
            </div>
            <?php
                if (isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['matricola']) && !empty($_SESSION['matricola']))
                {
                    $email = $_SESSION['email'];
                    $matricola = $_SESSION['matricola'];
                    if(isset($_SESSION['username']) && !empty($_SESSION['username']))
                        $username = $_SESSION['username'];
            ?>
            <div class='showMatricola'>
                <?php 
                    echo "<p>$matricola</p>";
                    if(!empty($username))
                        echo "<p>$username</p>";
                ?>
            </div>
            <div class="buttonContainerProfile">
                <div id='anagraficaButton'><a href="#">Anagrafica</a></div>
                <div id='puntiButton'><a href="#">Punti</a></div>
                <div id='postButton'><a href="#">Attività</a></div>
            </div>
        </div>
        <div id='anagrafica'>
            <div class='showInfo'>

                <?php
                    echo "<div id='modifica'><a href='#'><img src='../img/gear.png' alt='gear'></a></div>";
                    
                    echo "<div class='anagraficaText'>";
                    if (!empty($username))
                        echo ("<div class='singleTextLeft'><p>Username</p></div><div class='singleTextRight'><p> " . $username . "</p></div>");
                    echo ("<div class='singleTextLeft'><p>Matricola</p></div><div class='singleTextRight'><p> " . $matricola . "</p></div><div class='singleTextLeft'><p>Email</p></div><div class='singleTextRight'><p>" . $email . "</p></div>");
                    echo "</div>";
                    if (!empty($_SESSION['errorMessage']))
                        echo ("<div class='error'>" . $_SESSION['errorMessage'] . "</div>");
                    if (!empty($_SESSION['okMessage']))
                        echo ("<script>alert('" . $_SESSION['okMessage'] . "');</script>");
                    unset($_SESSION['errorMessage']);
                    unset($_SESSION['okMessage']);
                ?>
                    <div id='modify'>
                        <div class="modificaUser">
                            <p>Modifica username</p>
                            <form method="POST" action="update.php">
                                <input class='input' type="text" name="username" placeholder="<?php if (!empty($username)) echo $username; else echo 'Username' ?>" required autocomplete="username">
                                <input type="submit" class="buttonModifica" value="save">
                            </form>
                        </div>
                        <div class="modificaPwd">
                            <p>Modifica password</p>
                            <form method="POST" action="update.php">
                                <input class='input' type="password" name="password" placeholder="password" required autocomplete="new-password">
                                <input class='input' type="password" name="passwordrepeat" placeholder="password" required>
                                <input type="submit" class="buttonModifica" value="save">
                            </form>
                        </div>
                    </div>
            </div>
        </div>
        <div id="post">
            <div class="showPost">
                <?php
                    if (!$res = retrieveMessagesFromSN($conn,$matricola))
                        internalError("Impossibile recuperare i messaggi scritti dall'utente", "profile");
                    while ($row = $res->fetch_assoc()) {
                        echo "<div class='singlePost'><div class='textPost'>".$row['Testo']."</div>";
                        $timestamp = strtotime($row['DataPubblicazione']);
                        $data = date("d-m-Y", $timestamp);
                        echo "<div class='data'>".$data."</div></div>";
                        if(!empty($row['Nome']))
                            echo "<div class='singlePost'><div class='filePost'><img class='fileImg' src = '../img/document.png' alt='file'><p>".$row['Nome']."</p></div><div class='data'>".$data."</div></div>";
                    }
                ?>
            </div>
        </div>
        <div id="punti">
            <div class='showInfo'>
                <?php
                    $points = retrieveAllPoints($conn,$matricola);
                    if ($points == -1)
                        internalError("Impossibile recuperare i punti totali dell'utente", "profile");
                    echo "<div class='showPoints'><div class='pointsBig'>" . $points . "</div>";
                    echo "<div class='puntiSingoli'><p>Punti accumulati <br>con la partecipazione al forum</p></div></div>";
                    if ($points > 0) {
                        echo ("<div class='puntiSingoli'><a href='#'>Mostra per singoli insegnamenti</a></div>");
                        $res = retrievePointsFromSN($conn,$matricola);
                        if ($res === -1)
                            internalError("Impossibile recuperare i punti dell'utente per i singoli insegnamenti", "profile");
                        echo "<div id='showSingle'><div class='showSinglePoint'>";
                        while ($row = $res->fetch_assoc())
                            echo ("<div class='singleRowPoints'><div><p>".$row['Nome'] . "</p></div><div class='pointsSmall'>" . $row['Points'] . "</div></div>");
                        echo "</div></div>";
                    }
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>