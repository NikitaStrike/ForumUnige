<?php
    session_start();
    require_once('util/adminFunction.php');
    require_once('util/utilError.php');
    require_once('util/retrieve.php');
    require_once('util/connection_config.php');
    require_once('util/connection.php');
    if(isset($_SESSION['matricola']) && !empty($_SESSION['matricola']))
    {
        if(isset($_GET['id']) && isset($_GET['thread']))
        {
            $mexId = $_GET['id'];
            $thread = $_GET['thread'];
            $userId = $_SESSION['matricola'];
            $conn = connection();
            $file = retrieveFileNameFromMex($conn,$mexId);
            if($file===-1)
                internalError("Impossibile recuperare il nome del file da cancellare","elimina");                    
            if($file!=0) 
                if(!deleteFile($userId,$file))
                    internalError("Impossibile cancellare il file associato al messaggio da eliminare","elimina");
            if(!deleteMessage($conn,$mexId))
                errorSiteAttribute("Impossibile eliminare il messaggio","singleThread","idThread",$thread);
            $conn->close();
            header("Location:singleThread.php?idThread=$thread");
            exit();
        }
    }
?>
