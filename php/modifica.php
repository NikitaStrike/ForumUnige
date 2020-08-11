<?php
    session_start();
    require_once('util/retrieve.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');
    if(isset($_SESSION['email']))
    {
        if(!empty($_GET['thread']) && !empty($_GET['id']))
        {
            $conn = connection();
            $thread = $_GET['thread'];
            $mex = $_GET['id'];
            $text = retrieveTextFromId($conn,$mex);
            $_SESSION['textThread']=$text;
            $_SESSION['modify'] = "on";
            $conn->close();
            header("Location:singleThread.php?idThread=$thread&mexId=$mex");
            exit();
        }
            internalError("Impossibile recuperare le informazioni riguardanti il thread","modifica");
    }
        echo("Utente non registrato");
?>