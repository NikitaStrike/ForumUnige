<?php
session_start();
require_once('util/testExistence.php');
require_once('util/retrieve.php');
require_once('util/utilError.php');
require_once('util/utilFunction.php');
require_once('util/connection.php');
?><!DOCTYPE HTML>
<html lang="it">
<head>
    <title><?php if (isset($_GET['course'])) echo $_GET['course']; ?></title>
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
        if (isset($_SESSION['email'])) {
            if (!empty($_GET["course"])) {
                $conn = connection();
                $nameCourse = $_GET['course'];
                if (checkNameCourseExists($conn,$nameCourse)) {
                    $_SESSION['course'] = $nameCourse;
                    echo "<div class='nameCourse'><h3>$nameCourse</h3></div></div>";
                    echo "<div class='boxCourse'><div class='itemBoxCourse'><a href='singleForum.php'>Forum</a></div>";
                    echo "<div class='itemBoxCourse'><a href='singleCourseFile.php'>Appunti</a></div></div>";
                } else
                    echo ("Corso non trovato");
                $conn->close();
            }
        } else {
            echo "</div>";
            echo "<div class='boxForLogin'><p>Effettua il login per avere accesso alle informazioni</p></div>";
        }
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>