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
    <title>ListaCorsi</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script  src="../js/util.js"></script>
    <script src="../js/externalJquery.js"></script>
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
        </div>
        <?php
        if (isset($_SESSION['matricola']) && !empty($_SESSION['matricola'])) {
            if (isset($_POST) && !empty($_POST)) {
                $conn = connection();
                if (!empty($_POST['nome'])) {
                    $nome = $_POST['nome'];
                    $res = retrieveWithSearch($conn,$nome);
                    if (is_int($res)) {
                        if ($res == -1)
                            internalError("Impossibile eseguire la ricerca", "searchCourse");
                        if ($res == 0)
                            echo "Nessun risultato trovato.<br><a href='coursesList.php'>Ripeti ricerca</a>";
                    }
                }
                if (!empty($_POST['corsoDiLaurea'])) {
                    $corso = $_POST['corsoDiLaurea'];
                    if (!empty($_POST['anno'])) {
                        $anno = $_POST['anno'];
                        if (!$res = retrieveTeachingFromCourseAndYear($conn,$corso, $anno))
                            internalError("Impossibile recuperare gli insegnamenti cercati", "searchCourse");
                    } else {
                        if (!$res = retrieveTeachingFromCourse($conn,$corso))
                            internalError("Impossibile recuperare gli insegnamenti cercati tramite CdL", "searchCourse");
                    }
                } else if (!empty($_POST['anno'])) {
                    $anno = $_POST['anno'];
                    if (!$res = retrieveTeachingFromYear($conn,$_POST['anno']))
                        internalError("Impossibile recuperare gli insegnamento cercati tramite anno", "searchCourse");
                }
                if (!empty($corso) && !empty($anno))
                    echo ("<h3>Risultati della ricerca per il CdL " . stripslashes($corso) . ", " . $anno . " anno</h3>");
                else if (!empty($corso))
                    echo ("<h3>Risultati della ricerca per il CdL " . stripslashes($corso) . "</h3>");
                else if (!empty($anno))
                    echo ("<h3>Risultati della ricerca per il " . $anno . " anno </h3>");

                echo ("<div class='containerList'>");
                while ($row = $res->fetch_assoc()) {
                    $userId = $_SESSION['matricola'];
                    $followed = checkIfFollowed($conn,$row['Nome'], $userId);
                    if($followed ===-1)
                        internalError("Impossibile recuperare le informazioni riguardanti l'insegnamento", "searchCourse");

                        echo ("<div class='singleItem'><div class='singleItemText'><div class='nomeSemestre'><a href='singleCourse.php?course=" . $row['Nome'] . "'><b>" . $row['Nome']."</b></a><p>(".$row['Semestre'] . ")</p></div><div class='codiceCfu'><p>".$row['Codice']."</p><p>".$row['CFU']. " CFU</p></div></div>");
                        if($followed == 0)
                            echo ("<a href='segui.php?anno=" . $row['VersioneAnno'] . "&cod=".$row['Codice']."'><img id='emptyStar' src='../img/star_border-36px.png' onclick='changeToFullStar()' alt='emptyStar'></a></div>");
                        else 
                            echo ("<a href='segui.php?anno=" . $row['VersioneAnno'] . "&cod=".$row['Codice']."&unfollow=on'><img id='fullStar' src='../img/full_star_36px.png' onclick='changeStar()' alt='fullstar'></a>");
                }
                echo ("</div>");
                $conn->close();
            } else {
                header("Location:coursesList.php");
                exit();
            }
        } else 
            echo ("<div class='boxForLogin'><p>Effettua il login o registrati per avere accesso al forum</p></div>");
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>