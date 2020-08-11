<?php
    session_start();

    require_once('util/retrieve.php');
    require_once('util/adminFunction.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');
    if(isset($_GET) && !empty($_GET))
    {
        $courseName = $_GET['courseName'];
        $mexId = $_GET['mex'];
        $threadId = $_GET['idThread'];
        
        if(isset($_SESSION['matricola']) && !empty($_SESSION['matricola']))
        {
            $conn = connection();
            $userId = $_SESSION['matricola'];

            if(!$codInsegnamento = retrieveTeachingIdFromName($conn,$courseName))
                internalError("Impossibile recuperare il codice dell'insegnamento","like");

            $res = checkLikeAlreadyExists($conn,$userId,$mexId,$codInsegnamento);
            switch($res)
            {
                case -1:
                    internalError("Impossibile sapere se il like è già stato dato","like");
                    break;
                case 0:
                    if(!insertPunti($conn,$userId, $mexId, $codInsegnamento))
                        internalError("Impossibile salvare il like","like");
                    break;
                case 1:
                    if(!deleteLike($conn,$userId,$mexId,$codInsegnamento))
                        internalError("Impossibile cancellare il like","like");
                    break;
            }
            $conn->close();
            header("Location:singleThread.php?idThread=$threadId");
            exit();
        }
        else
            errorSiteAttribute("Utente non registrato","singleThread","idThread",$threadId); 
    }
    else
        internalError("Impossibile recuperare le informazioni riguardanti il messaggio","like");
?>