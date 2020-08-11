<?php
session_start();

require_once('util/retrieve.php');
require_once('util/testExistence.php');
require_once('util/utilError.php');
require_once('util/utilFunction.php');
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">
<head>
    <title>Forum</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script  src="../js/util.js"></script>
    <script  src="../js/externalJquery.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class='content'>
        <div class='blueHeader'>
            <div class='headerLink'>
                <div class='homeBox'>
                    <div class='homeLogo'>
                        <a href="home.php"><img src="../img/logo_UniGe_WHITE.png" alt="logo"></a>
                    </div>
                </div>
                <div class='searchBox'>
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
                else 
                {
                    echo "<div class='accountBox'><a href='login.php'>ACCEDI</a></div>";
                    echo "<div class='mobileLoggedBox'><a href='login.php'><img src='../img/person_36px.png' alt='person'></a></div>";
                }
                ?>
            </div>
        <?php
        if (isset($_SESSION['matricola']) && !empty($_SESSION['matricola'])) {
            if (!empty($_SESSION['course'])) {
                $conn = connection();
                $nameCourse = $_SESSION['course'];
                if (checkNameCourseExists($conn,$nameCourse)) {
                        echo ("<div class='nameCourse'><h3>$nameCourse</h3></div>");
                        echo ("<div class='createThread'><a href='createThread.php'>Crea thread</a></div></div>");
                        if (!$codForum = retrieveForumIdFromName($conn,$nameCourse))
                            internalError("Impossibile recuperare il codice del forum", "singleForum");
                        if (!$res = retrieveThreadFromForum($conn,$codForum))
                            internalError("Impossibile recuperare i thread del forum", "singleForum");
                        echo "<div class='containerMessageList'>";
                        while ($row = $res->fetch_assoc()) {
                        $close = checkIfClose($row['Id']);
                        if ($close == -1)
                            internalError("Impossibile sapere se il thread è chiuso", "singleForum");
                        $resolved = checkIfResolved($conn,$row['Id']);
                        if ($resolved == -1)
                            internalError("Impossibile sapere il thread è risolto", "singleForum");
                        if (!$close && !$resolved)
                            echo ("<div class='singleMessage'><a href='singleThread.php?idThread=" . $row['Id'] . "'>" . $row['Titolo'] . "</a>" . $row['Domanda'] . "");
                        if ($resolved)
                            echo ("<div class='singleMessage'><a href='singleThread.php?risolto=true&idThread=" . $row['Id'] . "'>[Risolto]" . $row['Titolo'] . "</a>" . $row['Domanda'] . "");
                        if (!$user = retrieveStudentFromThreadId($conn,$row['Id']))
                            internalError("Impossibile recuperare l'utente autore del thread", "singleForum");
                        if ($user == $_SESSION['matricola']) {
                            if (!$close && !$resolved) {
                                echo ("<div class='singleMessageIcons'><a href=chiudi.php?close=on&idThread=" . $row['Id'] . "'><img src='../img/close.png' alt='close'></a>");
                                echo ("<a href='chiudi.php?res=on&idThread=" . $row['Id'] . "'><img src='../img/check.png' alt='risolvi'></a></div>");
                                echo ("<div class='singleMessageHeart'><img src='../img/cuore.png' alt='heart'></div>");
                            }
                        }
                        echo ("</div>");
                        }
                        echo "</div>";
                }
            }
            $conn->close();
        } else {
            echo ("</div>");
            echo ("<div class='boxForLogin'><p>Registrati o effettua il login per visualizzare le informazioni</p></div>");
        }
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>