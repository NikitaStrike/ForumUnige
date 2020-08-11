<?php
   session_start();

    require_once('util/retrieve.php');
    require_once('util/adminFunction.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');
    if(isset($_SESSION['matricola']) && !empty($_SESSION['matricola']))
    {
        $userId = $_SESSION['matricola'];
        if(isset($_GET['anno']) && isset($_GET['cod']))
        {
            $conn = connection();
            $verAnno  = $_GET['anno'];
            $codInsegnamento = $_GET['cod'];
            if(isset($_GET['unfollow']))
            {
                if(!deleteFollowedTeaching($conn,$codInsegnamento,$verAnno,$userId))
                    internalError("Impossibile eliminare l'insegnamento dai preferiti","segui");
            }
            else
            {
                if(!insertFollowedTeaching($conn,$userId,$codInsegnamento,$verAnno))
                    internalError("Impossibile aggiungere l'insegnamento ai preferiti","segui");
            }
            $conn->close();
            header("Location:coursesList.php");
            exit();      
        } 
    }
