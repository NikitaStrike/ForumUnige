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
    <title>CreateThread</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script  src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script  src="../js/util.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script  src="../js/externalJquery.js"></script>
    <script  src="../ckeditor/ckeditor.js"></script>
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
                <div class="searchBox">
                    <form name="cerca" method="post" action="cerca.php">
                        <div class='searchIcons'>
                            <input class='specialSearch' type="text" id="search" name="nome" placeholder="cerca">
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
            if (!empty($_SESSION['course'])) {
                $courseName = $_SESSION['course'];
                echo ("<div class='nameCourse'><h3>" . $courseName . "</h3></div></div>");
            ?> 
            <div class="containerCreateThread">
            <?php
                $conn = connection();
                if (!empty($_SESSION["errorMessage"]))
                {
                    echo ("<div class='errorAfterPost'><p>" . $_SESSION["errorMessage"] . "</p></div>");
                    unset($_SESSION['errorMessage']);
                }
                if (checkNameCourseExists($conn,$courseName)) {
                    echo ("<form method='POST' action='forumCreateThread.php' enctype='multipart/form-data'>");
                    $title = $text = $tags = $tagsFile = "";
                    if (!empty($_SESSION['titleThread']))
                    {
                        $title = $_SESSION['titleThread'];
                        unset($_SESSION['titleThread']);
                    }
                    if (!empty($_SESSION['textThread']))
                    {
                        $text = $_SESSION['textThread'];
                        unset($_SESSION['textThread']);
                    }
                    if (!empty($_SESSION['tagsThread']))
                    {
                        $tags = $_SESSION['tagsThread'];
                        unset($_SESSION['tagsThread']);
                    }
                    if (!empty($_SESSION['tagsFile']))
                    {
                        $tagsFile = $_SESSION['tagsFile'];
                        unset($_SESSION['tagsFile']);
                    }
                    echo "<input class='input' type='text' name='title' placeholder='Title' value='" . $title . "' required>";
                    echo "<textarea rows='5' cols='38' name='textThread' id='textThread'>$text</textarea>";
                    echo "<script type='text/javascript'>";
                    echo "CKEDITOR.replace( 'textThread' );";
                    echo "</script>";
                    echo "<label>Inserire uno o più tag per il thread</label><br>";
                    echo "<input type='text' id='tagsThread' name='tagsThread' value='" . $tags . "'>";
                    echo "<br><label>Upload file</label>";
                    echo "<input type='file' name='userfile' value='Scegli file' accept='application/pdf'>";
                    echo "<label>Inserire uno o più tag per descrivere il file</label>";
                    echo "<input type='text' id='tags' name='tags' value='" . $tagsFile . "'>";
                    echo "<div class='buttonContainer'><input type='submit' class='buttonPost' value='post your answer'></div>";
                    echo "</form>";
                }
            }
            ?>
        </div>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>
</html>