<?php
    session_start();
    require_once('util/testExistence.php');
    require_once('util/adminFunction.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');

    if(isset($_GET['idThread']))
    {
        $idThread = $_GET['idThread'];
        $conn = connection();
        if(!checkThreadExists($conn,$idThread))
            internalError("Impossibile trovare il thread","chiudi");
        if(isset($_GET['close']))
        {
            if(!closeThread($conn,$idThread))
                internalError("Impossibile chiudere il thread", "chiudi");
            $conn->close();
            header("Location:singleForum.php");
            exit();
        }
        if(isset($_GET['res']))
        {
            if(!resolveThread($conn,$idThread))
                internalError("Impossibile segnare come risolto il thread","chiudi");
            $conn->close();
            header("Location:singleForum.php");
            exit(); 
        }
    }
