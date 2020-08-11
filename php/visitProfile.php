<?php
session_start();
require_once("util/testExistence.php");
require_once("util/retrieve.php");
require_once("util/utilError.php");
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">
<head>
    <title>Profilo</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script  src="../js/util.js"></script>
    <script src="../js/externalJquery.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="content">
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
                if (isset($_SESSION["matricola"]) && !empty($_SESSION["matricola"])) {
                    $proName = $_SESSION['matricola'];
                    if(isset($_SESSION['username']) && !empty($_SESSION['username']))
                        $proName = $_SESSION['username'];
                    echo ("<div class='loggedBox'><div class='profileBoxExit'><div class='profileBox'><img src='../img/person.png' alt='person'><a href='show_profile.php'>" . $proName . "</a></div><div class='profileBox'><img src='../img/exit.png' alt='exit'><a href='logout.php'>Esci</a></div></div></div>");
                    echo ("<div class='mobileLoggedBox'><a href='show_profile.php'><img src='../img/person_36px.png' alt='person'></a></div>");
                }
                ?>
            </div>
            <?php
            $conn = connection();
                if (isset($_GET['profile'])) 
                {
                    $user = $_GET['profile'];
                    if (checkSNExists($conn,$user)) 
                        echo "<div class='nameUser'><h3>".$user."</h3></div>";
            ?>
            <div class="buttonContainerProfile">
                <div id='puntiButton'><a href="#">Punti</a></div>
                <div id='postButton'><a href="#">Attività</a></div>
            </div>
        </div>    
                <div id="post">
                <div class="showPostVisit">
                    <?php
                        if (!$res = retrieveMessagesFromSN($conn,$user))
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
            <div class='showInfoVisit'>
                <?php
                    $points = retrieveAllPoints($conn,$user);
                    if ($points == -1)
                        internalError("Impossibile recuperare i punti totali dell'utente", "profile");
                    echo "<div class='showPoints'><div class='pointsBig'>" . $points . "</div>";
                    echo "<div class='puntiSingoli'><p>Punti accumulati <br>con la partecipazione al forum</p></div></div>";
                    if ($points > 0) {
                        echo ("<div class='puntiSingoli'><a href='#'>Mostra per singoli insegnamenti</a></div>");
                        $res = retrievePointsFromSN($conn,$user);
                        if (is_int($res) && $res === -1)
                            internalError("Impossibile recuperare i punti dell'utente per i singoli insegnamenti", "profile");
                        echo "<div id='showSingle'><div class='showSinglePoint'>";
                        while ($row = $res->fetch_assoc())
                            echo ("<div class='singleRowPoints'><div><p>".$row['Nome'] . "</p></div><div class='pointsSmall'>" . $row['Points'] . "</div></div>");
                        echo "</div></div>";
                    }
            $conn->close();
        }
        else
            echo "Utente non indicato";
                ?>
            </div>
        </div>    
    </div>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>