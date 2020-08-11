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
    <title>Thread</title>
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
    <script  src="../js/util.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
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
                else 
                {
                    echo "<div class='accountBox'><a href='login.php'>ACCEDI</a></div>";
                    echo "<div class='mobileLoggedBox'><a href='login.php'><img src='../img/person_36px.png' alt='person'></a></div>";
                }
                ?>
            </div>
        <?php
        if (isset($_SESSION['matricola']) && !empty($_SESSION['matricola'])) {
            $userId = $_SESSION['matricola'];
            $conn = connection();
            if(isset($_GET['idThread'])) {
                $idThread = $_GET['idThread'];
                if (checkThreadExists($conn,$idThread)) {
                    if (!$threadName = retrieveThreadNameFromId($conn,$idThread))
                        internalError("Impossibile recuperare l'id del thread", "singleThread");
                    
                    if (!$courseName = retrieveTeachingNameFromThread($conn,$idThread))
                        internalError("Impossibile recuperare il nome dell'insegnamento", "singleThread");
                    $_SESSION['threadName'] = $threadName;
                    $_SESSION['forumName'] = $courseName;
                    echo ("<div class='nameCourse'><h3>$threadName</h3></div></div>");
                    if (!$res = retrieveMessagesInThread($conn,$idThread))
                        internalError("Impossibile recuperare i messaggi del thread","singleThread");

                    $mexId = "";
                    echo ("<div class='containerMessageListST'>");
                    while ($row = $res->fetch_assoc()) {
                        $mexId = $row['MexId'];
                        echo ("<div class='singleMessage'>");
                        echo ($row['Testo']);
                        echo ("<div class='infoMessage'><p>Pubblicato:" . $row['DataPubblicazione'] . "</p>");
                        if ($row['Studente'] != $userId)
                            echo ("<p>Da: <a href='visitProfile.php?profile=" . $row['Studente'] . "'>" . $row['Studente'] . "</a></p>");
                        else
                            echo ("<p>Da: " . $row['Studente'] . "</p>");
                            if (!empty($row['Nome']))
                            echo ("<a href='download.php?id=" . $row['Id'] . "' target='_blank'>" . $row['Nome'] . "</a><br>");
                        $likes = retrieveLikesForMessage($conn,$row['MexId']);
                        if ($likes == -1)
                            internalError("Impossibile calcolare il numero di like", "singleThread");
                        echo "</div>";
                        echo ("<div class='singleMessageHeart'><p>$likes <a href='like.php?courseName=$courseName&idThread=$idThread&mex=" . $row['MexId'] . "'><img class='likesImg' src='../img/cuore.png' alt='like'></a></p></div>");
                        echo "<div class='singleMessageIcons'>";
                        if ($row['Studente'] == $userId)
                            echo ("<div class='likes'><div class='numlikes'><p>$likes</p></div><img class='likesImg' src='../img/cuore.png' alt='cuore'></div>");
                        else
                            echo ("<p>$likes <a href='like.php?courseName=$courseName&idThread=$idThread&mex=" . $row['MexId'] . "'><img class='likesImg' src='../img/cuore.png' alt='like'></a></p>");
                       
                        if ($row['Studente'] == $userId) {
                            if (!isset($_GET['risolto']))
                                echo ("<a href='modifica.php?thread=$idThread&id=" . $row['MexId'] . "'><img src='../img/create.png' alt='modifica'></a>");
                            $resCheck = checkIfFirstQuestion($conn,$row['MexId']);
                            if ($resCheck == -1)
                                internalError("Impossibile ottenere lo stato del messaggio", "singleThread");
                            if ($resCheck == 0)
                                echo ("<a href='elimina.php?thread=$idThread&id=" . $row['MexId'] . "'><img src='../img/close.png' alt='elimina'></a>");
                        }
                        echo ("</div></div>");
                    }
                    echo "</div>";
                    if (isset($_GET['mexId']))
                        $mexId = $_GET['mexId'];
                    $res = checkIfResolved($conn,$idThread);
                    if ($res == -1)
                        internalError("Impossibile ottenere lo stato del messaggio", "singleThread");
                    if ($res == 0) {
                        echo "<div class='containerQA'>";
                        if (!empty($_SESSION['errorMessage']))
                        {
                            echo "<script>jumpTo('formModifica');</script>";
                            echo ("<div class='errorAfterPost'><p>" . $_SESSION['errorMessage'] . "</p></div>");
                            unset($_SESSION['errorMessage']);
                        }
                        echo "<h3>Your answer</h3>";
                        echo "<a name='formModifica'>";
                        if (!empty($_SESSION['modify']))
                        {   
                            echo "<script>jumpTo('formModifica');</script>";
                            echo ("<form method='post' action='singleThreadAnswer.php?idThread=$idThread&mexId=$mexId' enctype='multipart/form-data'>");
                            unset($_SESSION['modify']);
                        }
                        else
                            echo ("<form method='post' action='singleThreadAnswer.php?idThread=$idThread' enctype='multipart/form-data'>");

                        $textThread = "";
                        if (!empty($_SESSION['textThread']))
                        {
                            $textThread = $_SESSION['textThread'];
                            unset($_SESSION['textThread']);
                        }
                        $tags = "";
                        if (!empty($_SESSION['tagsFile']))
                        {
                            $tags = $_SESSION['tagsFile'];
                            unset($_SESSION['tagsFile']);
                        }
                        echo "<textarea rows='5' cols='38' name='textThread' id='textThread'>$textThread</textarea>";
                        echo "<script type='text/javascript'>";
                        echo "CKEDITOR.replace('textThread')";
                        echo "</script>";
                        echo "<label>Upload file</label>";
                        echo "<input type='file' name='userfile' value='Scegli file' accept='application/pdf'>";
                        echo "<label>Inserire uno o più tag per descrivere il file</label>";
                        echo "<input type='text' id='tags' name='tags' value='" . $tags . "'>";
                        echo "<div class='buttonContainer'><input type='submit' class='buttonPost' value='post your answer'></div>";
                        echo "</form>";
                        echo "</div>";
                    }
                } else
                    echo ("Thread non trovato");
            }
            $conn->close();
        } else {
            echo "</div>";
            echo "<div class='boxForLogin'><p>Registrati o effettua il login</p></div>";
        }
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>