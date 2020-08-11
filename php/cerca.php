<?php
session_start();
require_once('util/retrieve.php');
require_once('util/utilError.php');
require_once('util/connection.php');
?>
<!DOCTYPE HTML>
<html lang="it">

<head>
    <title>Cerca</title>
    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
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
                } else {
                    echo "<div class='accountBox'><a href='login.php'>ACCEDI</a></div>";
                    echo "<div class='mobileLoggedBox'><a href='login.php'><img src='../img/person_36px.png' alt='person'></a></div>";
                }

                ?>
            </div>
        </div>
        <?php
        if (isset($_POST) && !empty($_POST)) {
            $conn = connection();
            $tag = $_POST['nome'];
            $result = retrieveTag($conn, $tag);
            if (is_int($result)) {
                if ($result === -1)
                    internalError("Impossibile eseguire la ricerca sui tag", "cerca");
                if ($result == 0)
                    echo ("<div class='resultTag'><p>Nessun risultato trovato per il tag [ " . $tag . " ]</p></div>");
            } else {
                echo "<div class='resultTag'><h3>Risultati per il tag [ " . $tag . " ]</h3></div>";
                echo "<div class='showResult'>";
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['Insegnamento'])) {
                        if (!$nomeInsegnamento = retrieveTeachingNameFromId($conn, $row['Insegnamento']))
                            internalError("Impossibile recuperare il nome dell'insegnamento", "cerca");
                        echo ("<div class='singleItemSearch'><div class='singleItemSearchIT'><img src='../img/school.png' alt='school'><div><a href='singleCourse.php?course=$nomeInsegnamento'>$nomeInsegnamento</a></div></div></div>");
                    }
                    if (!empty($row['File'])) {
                        if (!$nomeFile = retrieveFileNameFromId($conn,$row['File']))
                            internalError("Impossibile recupeare il nome del file", "cerca");
                        echo ("<div class='singleItemSearch'><div class='singleItemSearchIT'><img src='../img//file_black.png' alt='file'><p><a href='download.php?id=" . $row['File'] . "' target='_blank'>$nomeFile</p></div></div>");
                    }
                    if (!empty($row['Thread'])) {
                        if (!$threadName = retrieveThreadNameFromId($conn, $row['Thread']))
                            internalError("Impossibile recupeare il nome del thread", "cerca");
                        echo ("<div class='singleItemSearch'><div class='singleItemSearchIT'><img src='../img/chat.png' alt='chat'><p><a href='singleThread.php?idThread=" . $row['Thread'] . "'>$threadName</a></p></div></div>");
                    }
                }
                echo "</div>";
            }
        }
        $conn->close();
        ?>
    </div>
    <footer>
        <img src="../img/logo_UniGe_BLACK.png" alt="logo">
    </footer>
</body>

</html>