<?php
session_start();
require_once('util/retrieve.php');
require_once('util/utilError.php');
require_once('util/utilFunction.php');
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">

<head>
    <title>ListaCorsi</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script  src="../js/util.js"></script>
    <script  src="../js/externalJquery.js"></script>

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
                    } else 
                    {
                        echo "<div class='accountBox'><a href='login.php'>ACCEDI</a></div>";
                        echo "<div class='mobileLoggedBox'><a href='login.php'><img src='../img/person_36px.png' alt='person'></a></div>";
                    }
                ?>
            </div>
            <div class='nameCourse'><h2>Insegnamenti</h2></div></div>
        <div class='searchForm'>
            <form name="cerca" method="POST" action="searchCourse.php">
                <input class="searchNome" type="text" id="searchIns" name="nome" placeholder="Nome">
                <select name="corsoDiLaurea" id="corsodiLaurea">
                    <option value="">Corso di Laurea</option>
                    <option value="Informatica">Informatica</option>
                    <option value="Design del prodotto e dell\'evento">Design del prodotto e dell'evento</option>
                </select>
                <select name="anno" id="anno">
                    <option value="">Anno</option>
                    <option value="primo">Primo</option>
                    <option value="secondo">Secondo</option>
                    <option value="terzo">Terzo</option>
                </select>
                <input type="submit" class="searchButton" value="cerca">
            </form>
        </div>
        <?php
        $conn = connection();
        if (isset($_SESSION['matricola']) && !empty($_SESSION['matricola'])) {
            $userId = $_SESSION['matricola'];
            if (!$res = retrieveTeachingFollowed($conn,$userId))
                internalError("Impossibile recuperare gli insegnamenti seguiti", "coursesList");

            echo ("<div class='containerList'>");
            while ($row = $res->fetch_assoc()) {
                echo ("<div class='singleItem'><div class='singleItemText'><div class='nomeSemestre'><a href='singleCourse.php?course=" . $row['Nome'] . "'><b>" . $row['Nome']."</b></a><p>(".$row['Semestre'] . ")</p></div><div class='codiceCfu'><p>".$row['Codice']."</p><p>".$row['CFU']. " CFU</p></div></div>");
                echo ("<div class='star'><a href='segui.php?anno=" . $row['VersioneAnno'] . "&cod=".$row['Codice']."&unfollow=on'><img id='fullStar' src='../img/full_star_36px.png' onclick='changeStar()' alt='fullstar'></a></div>");
                echo ("</div>");
            }
            echo ("</div>");

            if (!$res = retrieveAllTeachingNotFollowed($conn, $userId))
                internalError("Impossibile recuperare gli insegnamenti non seguiti", "coursesList");

            echo ("<div class='containerList'>");
            while ($row = $res->fetch_assoc()) {
                echo ("<div class='singleItem'><div class='singleItemText'><div class='nomeSemestre'><a href='singleCourse.php?course=" . $row['Nome'] . "'><b>" . $row['Nome']."</b></a><p>(".$row['Semestre'] . ")</p></div></a><div class='codiceCfu'><p>".$row['Codice']."</p><p>".$row['CFU']. " CFU</p></div></div>");
                echo ("<div class='star'><a href='segui.php?anno=" . $row['VersioneAnno'] . "&cod=".$row['Codice']."'><img id='emptyStar' src='../img/star_border-36px.png' onclick='changeToFullStar()' alt='emptyStar'></a></div></div>");
            }
            echo ("</div>");
        } else {
            if (!$res = retrieveAllTeaching($conn))
                internalError("Impossibile recuperare tutti gli insegnamenti", "coursesList");

            echo ("<div class='containerList'>");
            while ($row = $res->fetch_assoc())
                echo ("<div class='singleItem'><div class='singleItemText'><div class='nomeSemestre'><a href='singleCourse.php?course=" . $row['Nome'] . "'><b>" . $row['Nome']."</b></a><p>(".$row['Semestre'] . ")</p></div><div class='codiceCfu'><p>".$row['Codice']."</p><p>".$row['CFU']. " CFU</p></div></div></div>");
            echo ("</div>");
        }
        $conn->close();
        ?>

    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>
</html>