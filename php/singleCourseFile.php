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
    <title>AppuntiCorso</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/util.js"></script>
    <script src="../js/externalJquery.js"></script>
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
                $nameCourse = $_SESSION['course'];
                $conn = connection();
                if (checkNameCourseExists($conn,$nameCourse)) {
                    echo ("<div class='nameCourse'><h3>$nameCourse</h3></div></div>");
                    $res = retrieveAllFileFromCourse($conn,$nameCourse);
                    if ($res === -1)
                        internalError("Impossibile recuperare tutti i file dell'insegnamento", "singleCourseFile");
                    if ($res === 0)
                        echo ("<div class='boxForLogin'><p>Non sono stati caricati file per l'insegnamento</p></div>");
                    else
                    {
                        echo "<div class='fileContainer'>";
                        while ($row = $res->fetch_assoc()) {
                            $user = $_SESSION['matricola'];
                            echo "<div class='singleFile'><div class='singleFileIT'><img class='fileImg' src='../img/document.png' alt='file'><div class='singleFileText'><p>" . $row['Nome'] . "</p>";
                            if ($user === $row['Autore'])
                                echo ("<p>Autore: " . $row['Autore'] . "</p></div>");
                            else
                                echo ("<p>Autore: <a href='visitProfile.php?profile=" . $row['Autore'] . "'>" . $row['Autore'] . "</a></p></div>");
                            echo "</div><a href='download.php?id=" . $row['Id'] . "' target='_blank'><img src='../img/download.png' alt='download'></a></div>";

                            echo "<div class='singleFileSmall'>
                                    <div class='singleFileIT'>
                                        <img class='fileImg' src='../img/document.png' alt='file'>
                                            <div class='singleFileText'>
                                                <a href='download.php?id=" . $row['Id'] . "' target='_blank'>" . $row['Nome'] . "</a>";
                            if ($user === $row['Autore'])
                                echo ("<p>Autore: " . $row['Autore'] . "</p></div>");
                            else
                                echo ("<p>Autore: <a href='visitProfile.php?profile=" . $row['Autore'] . "'>" . $row['Autore'] . "</a></p></div>");
                            echo "</div></div>";
                        }
                    }
                }
                $conn->close();
            }
            echo "</div>";
        } else {
            
            echo ("<div class='boxForLogin'><p>Effettua il login o registrati per avere accesso alle informazioni</p></div>");
        }
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>