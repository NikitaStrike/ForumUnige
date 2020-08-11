<?php
    session_start();
    require_once('util/retrieve.php');
    require_once('util/testExistence.php');
    require_once('util/adminFunction.php');
    require_once('util/utilFunction.php');
    require_once('util/testInput.php');
    require_once('util/utilError.php');
    require_once('util/connection.php');
  
    if(isset($_POST) && !empty($_POST))
    {
        if(isset($_SESSION['matricola']) && !empty($_SESSION['matricola']) && isset($_SESSION['course']) && !empty($_SESSION['course']))
        {
            $courseName = $_SESSION['course'];
            $titleThread = $_POST['title'];
            $textThread = $_POST['textThread'];
            $userId = $_SESSION['matricola'];
            $conn = connection();

            if(!$forumId = retrieveForumIdFromName($conn,$courseName))
                    internalError("Impossibile recuperare l'id del forum","forumCreateThread");          

            $_SESSION['titleThread'] = $titleThread;
            if(empty($textThread))     
                errorSite("Il testo del messaggio non può essere vuoto","createThread");
            $_SESSION['textThread'] = $textThread;
            if(empty($_POST['tagsThread']))           
                errorSite("Inserire un tag valido per il thread","createThread");
            if(!empty($_POST['tagsThread']))
            {
                $tags = explode(",",$_POST['tagsThread']);
                foreach($tags as $t)
                {
                    if(!empty($t))
                    {
                        $t = trim($t);
                        $resultTag = checkTag($conn,$t);
                        if($resultTag===-1)
                            internalError("Impossibile completare la ricerca del tag", "forumCreateThread");
                        if($resultTag==0)
                            errorSite("Il tag ".$t." non esiste.Scegli i tag tra quelli proposti","createThread");
                    }
                }
            }
            $_SESSION['tagsThread'] = $_POST['tagsThread'];
            if(!empty($_POST['tags']))
            {
                $tags = explode(",",$_POST['tags']);
                foreach($tags as $t)
                {
                    if(!empty($t))
                    {
                        $t = trim($t);
                        $resultTag = checkTag($conn,$t);
                        if($resultTag===-1)
                            internalError("Impossibile completare la ricerca del tag", "forumCreateThread");
                        if($resultTag==0)
                            errorSite("Il tag ".$t." non esiste.Scegli i tag tra quelli proposti","createThread");
                    }
                }
            }
            $_SESSION['tagsFile'] = $_POST['tags'];
            if(!empty($_POST['tags']) && empty($_FILES["userfile"]["tmp_name"]))
                errorSite("Selezionare un file da caricare. Dimensione massima consentita: 2MB","createThread");
            if(empty($_POST['tags']) && !empty($_FILES["userfile"]["tmp_name"]))
                errorSite("Indicare almeno un tag per il file", "createThread"); 
            
            if(!empty($_FILES["userfile"]["tmp_name"]))
            {
                if(!checkName($_FILES["userfile"]["name"]))
                    errorSite("Il nome del file può contenere solo caratteri alfanumerici,-_.","createThread");
                if(checkLength($_FILES["userfile"]["name"]))
                    errorSite("Il nome del file deve essere inferiore ai 250 caratteri", "createThread");
                if(checkSize($_FILES["userfile"]["size"]))
                    errorSite("Dimensione massima consentita 2MB","createThread");
            }
            unset($_SESSION['titleThread']);
            unset($_SESSION['textThread']);
            unset($_SESSION['tagsThread']);
            unset($_SESSION['tagsFile']);
            unset($_SESSION['errorMessage']);

            if(!$threadId = insertThread($conn,$titleThread,$textThread,$forumId,$userId))          
                internalError("Impossibile inserire il thread","forumCreateThread");
            
            if(!$mexId = insertMessage($conn,$textThread,$threadId,$userId,1))
            {
                internalError("Impossibile inserire il messaggio. Sezione senza file","forumCreateThread");
                if(!deleteThread($conn,$threadId))
                    internalError("Impossibile cancellare il thread. Sezione senza file","forumCreateThread");
            }
            addTagThread($conn,$threadId,$_POST['tagsThread']);
            if(!empty($_FILES["userfile"]["tmp_name"]))
            {
                if(!uploadFile($_FILES["userfile"]["tmp_name"],$_FILES["userfile"]["name"],$userId))
                    internalError("Impossibile caricare il file","forumCreateThread");
                if(!$fileId = insertFile($conn,$_FILES["userfile"]["name"],$mexId,$userId))
                {
                    if(!deleteThread($conn,$threadId))
                        internalError("Impossibile cancellare il thread","forumCreateThread");
                    internalError("Impossibile salvare il file", "forumCreateThread");
                }
                addTag($conn,$fileId,$threadId,$_POST['tags']);
            }
                $conn->close();
                header("Location:singleForum.php");
                exit();   
        }
            else
                errorSite("Utente non registrato","createThread");
        }
        else    
        {
            unset($_SESSION['titleThread']);
            unset($_SESSION['textThread']);
            unset($_SESSION['tagsThread']);
            unset($_SESSION['tagsFile']);
            unset($_SESSION['errorMessage']);
            header("Location:singleForum.php");
            exit();
        }
